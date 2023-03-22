@foreach($languages as $language => $label)
    <div class="input-group mb5">
        <span class="input-group-addon">{{ $label }}</span>
            @if($inputs[$language] instanceof Digbang\Backoffice\Inputs\Composite)
                <div class="form-control">
            @endif
            {!! $inputs[$language]->render() !!}
            @if($inputs[$language] instanceof Digbang\Backoffice\Inputs\Composite)
                </div>
            @endif
    </div>
    @include('backoffice::inputs.errors.error', ['input' => $inputs[$language]])
@endforeach