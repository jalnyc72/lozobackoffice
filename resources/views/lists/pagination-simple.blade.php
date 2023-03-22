<div class="pagination-container">
	<span class="showing">{!! trans_choice('backoffice::paginator.showing', $paginator->total(), ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => '?']) !!}</span>
	@if ($paginator->hasPages())
		<ul class="pagination">
			<!-- Previous Page Link -->
			@if ($paginator->onFirstPage())
				<li class="disabled pagination-prev"><span>&laquo;</span></li>
			@else
				<li class="pagination-prev"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
			@endif

		<!-- Next Page Link -->
			@if ($paginator->hasMorePages())
				<li class="pagination-next"><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
			@else
				<li class="disabled pagination-next"><span>&raquo;</span></li>
			@endif
		</ul>
	@endif
</div>