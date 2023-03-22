<span data-toggle="modal" data-target="#{!! $id !!}">
    @include('backoffice::actions.link', compact($target, $options, $label))
</span>

@section('body.content')
@parent
<div id="{!! $id !!}" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
				<h4 class="modal-title">{!! $title !!}</h4>
			</div>
			<div class="modal-body">
				{!! $form->render() !!}
			</div>
		</div>
	</div>
</div>
@stop

@section('body.javascripts')
	@parent

	@if(session('modal_open') == $id)
		<script type="text/javascript">
		$(function() {
			$('#{{$id}}').modal('show');
		});
		</script>
	@endif
@stop