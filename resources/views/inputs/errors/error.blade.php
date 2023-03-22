@php($errorBag = $errors->get($input->name()) + $errors->get($input->dottedName()))
@foreach($errorBag as $error)
    <label for="{{ $input->name() }}" class="error">{!! $error !!}</label>
@endforeach
