<div class="col-flex form-group listing-filter
    {!! $size ?: 'col-sm-3' !!}
    {!! !is_null($value) ? 'listing-filter-filled' : 'listing-filter-hidden' !!}
    {!! $isVisible ? 'listing-filter-default' : '' !!}
">
    <label for="{{ $name }}">{{ $label }}</label>
    {!! $input->render() !!}
</div>
