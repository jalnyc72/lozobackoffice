<table class="table table-striped table-bordered table-responsive-list">
	<thead>
	<tr>
		@if($bulkActions && count($bulkActions))
		<th class="selectors">
			{!! Form::checkbox('all', 'all', null, ['class' => 'chk-all']) !!}
		</th>
		@endif
		@foreach($columns as $column)
		<th>
			@if($column->sortable())
				@include('backoffice::actions.sort', ['column' => $column])
			@else
				{!! $column->getLabel() !!}
			@endif
		</th>
		@endforeach
		@if($rowActions)
		<th>{!! Lang::get('backoffice::default.actions') !!}</th>
		@endif
	</tr>
	</thead>
	<tbody>
	@if(count($items))
	@foreach($items as $row)
	<tr>
		@if($bulkActions && count($bulkActions))
			@php($id = array_get($row, 'id'))
			<td>
				{!! Form::checkbox('row', $id, \in_array($id, $defaultSelection), ['class' => 'chk-bulk']) !!}
			</td>
		@endif
		@foreach($columns as $column)
		<td data-label="{{ $column->getLabel() }}">{!! \Illuminate\Support\Str::parse($column->getValue($row)) ?? '-' !!}</td>
		@endforeach
		@if($rowActions)
		<td class="row-actions">
			@php($hiddenActions = [])
			@php($i = 0)
			@foreach($rowActions as $action)
				@if($renderedAction = $action->renderWith($row))
					@if($i < $visibleRowActions)
						{!! $renderedAction !!}
					@else
						@php($hiddenActions[] = $renderedAction)
					@endif
					@php($i++)
				@endif
			@endforeach
			@if(!empty($hiddenActions))
				<div class="btn-group" data-toggle="tooltip" title="{!! trans('backoffice::default.more_actions') !!}">
					<a data-toggle="dropdown" class="text-default dropdown-toggle">
						<i class="fa fa-ellipsis-v"></i>
					</a>
					<ul role="menu" class="dropdown-menu pull-right js-actions-tooltip">
					@foreach($hiddenActions as $action)
						<li>{!! $action !!}</li>
					@endforeach
					</ul>
				</div>
			@endif
		</td>
		@endif
	</tr>
	@endforeach
	@else
	<tr>
		<td colspan="{!! count($columns) + 1 !!}" class="text-danger">
			{!! Lang::get('backoffice::default.empty_listing') !!}
		</td>
	</tr>
	@endif
	</tbody>
</table>

@section('body.javascripts')
	@parent
	<script type="text/javascript">
		$(function() {
			$('.js-actions-tooltip').mouseover(function() {
				$('[data-toggle="tooltip"]').tooltip('disable');
			});
			$('.js-actions-tooltip').mouseout(function() {
				$('[data-toggle="tooltip"]').tooltip('enable')
			});

			@if($defaultSelection)
				$('.actions-bulk').show();
		  	@endif
		});
	</script>
@stop
