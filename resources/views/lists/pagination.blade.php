<div class="pagination-container">
	<span class="showing">{!! trans_choice('backoffice::paginator.showing', $paginator->total(), ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) !!}</span>
	@if ($paginator->hasPages())
		<ul class="pagination">
			<!-- Previous Page Link -->
			@if ($paginator->onFirstPage())
				<li class="disabled pagination-prev"><span>&laquo;</span></li>
			@else
				<li class="pagination-prev"><a href="{{ $paginator->url(1) }}" rel="prev">&laquo;&laquo;</a></li>
				<li class="pagination-prev"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
			@endif

		<!-- Pagination Elements -->
			@foreach ($elements as $element)
			<!-- "Three Dots" Separator -->
				@if (is_string($element))
					<li class="disabled pagination-ellipsis"><span>{{ $element }}</span></li>
				@endif

			<!-- Array Of Links -->
				@if (is_array($element))
					@foreach ($element as $page => $url)
						@if ($page == $paginator->currentPage())
							<li class="active pagination-page"><span>{{ $page }}</span></li>
						@else
							<li class="pagination-page"><a href="{{ $url }}">{{ $page }}</a></li>
						@endif
					@endforeach
				@endif
			@endforeach

		<!-- Pagination Selector -->
			<li class="pagination-page-selector">
				@if($paginator->lastPage() < 20)
					@php
						$pages = [];
                        foreach(range(1, $paginator->lastPage()) as $pageNum) {
                            $pages[$paginator->url($pageNum)] = $pageNum;
                        }
					@endphp
					{!! Form::select('page-selector', $pages, $paginator->url($paginator->currentPage()), ['class' => 'page-selector', 'onchange' => 'location.href=this.value']) !!}
				@else
					<form method="get" style="position: relative; float: left;" onsubmit="
						var params = new URLSearchParams(window.location.search.slice(1));
						params.set('page', this.firstElementChild.value);
						location.href = '?' + params.toString();
						return false;
					">
						<input class="form-control" name="page" type="text" value="{{ $paginator->currentPage() }}" style="
						text-align: center;
						padding: 7px 3px;
						border-radius: 0;
						width: 90px;
						border-left: none;
						border-right: none;
						border-color: #ddd;
					"/>
						<input type="submit" class="btn btn-block btn-sm"  value="✓"/>
					</form>
				@endif
			</li>

			<!-- Next Page Link -->
			@if ($paginator->hasMorePages())
				<li class="pagination-next"><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
				<li class="pagination-next"><a href="{{ $paginator->url($paginator->lastpage()) }}" rel="next">&raquo;&raquo;</a></li>
			@else
				<li class="disabled pagination-next"><span>&raquo;</span></li>
			@endif
		</ul>
	@endif
</div>
