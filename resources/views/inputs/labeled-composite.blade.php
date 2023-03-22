<div {!! HTML::attributes($options) !!}>
    @foreach($inputs as $input)
        <div class="row mt10 labeled-composite-form-group">
            <div class="col-xs-3 text-right">
                <label for="{!! $input->option('id') !!}">
                    {!! $input->label() !!}
                </label>
            </div>
            <div class="col-xs-9">
                @include('backoffice::inputs.partials.composite-input', ['input' => $input])
            </div>
        </div>
    @endforeach
</div>