@php
    $options = [
        'class' => 'form-control form-time',
        'autocomplete' => 'off',
        'placeholder' => $label,
        'id' => $name
    ] + ($options ?? []);
@endphp

<div class="bootstrap-timepicker">
	{!! Form::text($name, $value, $options) !!}
</div>
