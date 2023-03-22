<div {!! HTML::attributes($options) !!}>
@foreach($inputs as $input)
    @include('backoffice::inputs.partials.composite-input', ['input' => $input])
@endforeach
</div>
