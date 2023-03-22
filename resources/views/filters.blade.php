{!! Form::open(['method' => 'GET', 'role' => 'form', 'class' => 'form-filter']) !!}
<div class="row row-flexbox">
    <div class="col-md-7 col-lg-9">
        <div class="listing-filters">
            @if($filters && count($filters))
                <div class="row row-flexbox">
                    @foreach($filters as $filter)
                        {!! $filter->setVisible($filters->shouldBeVisible($filter))->render() !!}
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-5 col-lg-3">
        <div class="form-group pull-right listing-filters-actions">
            {!! Form::submit(trans('backoffice::default.search'), ['class' => 'btn btn-primary hide', 'id' => 'search-filters-link']) !!}
            <a href="{!! $resetAction !!}" class="btn btn-default hide" id="reset-filters-link">{!! trans('backoffice::default.reset') !!}</a>
            @if($filters && count($filters))
                <a id="show-hide-filters-link" class="btn btn-link hide" href="#" data-show="{!! trans('backoffice::default.show_filters') !!}" data-hide="{!! trans('backoffice::default.hide_filters') !!}"></a>
            @endif
        </div>
    </div>
</div>
{!! Form::close() !!}

@section('body.javascripts')
    @parent

    <script type="text/javascript">
        $(document).ready(function() {
            var $showHideFiltersBtn = $('#show-hide-filters-link').removeClass('hide');
            var $searchFiltersBtn = $('#search-filters-link').removeClass('hide');
            var $resetFiltersBtn = $('#reset-filters-link').removeClass('hide');

            function showHideActions(){
                var filtersCount = $('.listing-filter').length;
                var filledCount = $('.listing-filter-filled').length;
                var defaultCount = $('.listing-filter-default').length;

                // If all filters all filled, then we can't hide them.
                // If all filters are default filters, we can't hide them either
                if (filtersCount == filledCount || filtersCount == defaultCount) {
                    $showHideFiltersBtn.addClass('hide');
                }

                if (!filledCount) {
                    $resetFiltersBtn.addClass('hide');
                }

                if (!filtersCount) {
                    $searchFiltersBtn.addClass('hide');
                    $resetFiltersBtn.addClass('hide');
                }
            }

            function toggleButton() {
                $showHideFiltersBtn.html($('.listing-filter-hidden').length ? $showHideFiltersBtn.data('show') : $showHideFiltersBtn.data('hide'));
                showHideActions();
            }

            function toggleFilters() {
                if ($('.listing-filter-hidden').length) {
                    $('.listing-filter-hidden').removeClass('listing-filter-hidden');
                } else {
                    $('.listing-filter').not('.listing-filter-filled').addClass('listing-filter-hidden');
                }
            }

            $showHideFiltersBtn.on('click', function() {
                toggleFilters();
                toggleButton();
            });
            toggleButton();
        });
    </script>
@overwrite
