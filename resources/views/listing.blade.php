@if($filters && count($filters))
	@include('backoffice::filters', ['filters' => $filters, 'resetAction' => $resetAction])
@endif
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="header-list">
					@if($actions || $bulkActions)
						@include('backoffice::lists.actions', ['actions' => $actions, 'bulkActions' => $bulkActions])
					@endif
					@if($paginator)
						{!! $paginator->render(($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? 'backoffice::lists.pagination' : 'backoffice::lists.pagination-simple')) !!}
					@endif
				</div>
				<div class="results-list">
					@include('backoffice::lists.list', ['bulkActions' => $bulkActions, 'columns' => $columns, 'rowActions' => $rowActions, 'items' => $items])
				</div>
			</div>
		</div>
	</div>
</div>
