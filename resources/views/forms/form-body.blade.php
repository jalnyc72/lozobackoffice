@foreach($inputs->getVisible() as $input)
    @continue(in_array($input->name(), $except) && $isTranslation)

    <div class="form-group{!! $errors->has($input->name()) || $errors->has($input->dottedName()) ? ' has-error' : '' !!}">
        <label for="{!! $input->name() !!}[{{ $language }}]" class="col-sm-3 control-label">
            {!! $input->label() !!}
        </label>
        <div class="col-sm-6">
            @if(in_array($input->name(), $except))
                {!! $input->render() !!}
            @else
                @if ($isTranslation)
                    <label for="{{ $input->name() }}[{{ $language }}]" class="js-trans" data-rel="{{ $input->name() }}[{{ reset($languages) }}]"></label>
                @endif
                {!! $input->translate([$language])->render() !!}
            @endif
        </div>
    </div>
@endforeach