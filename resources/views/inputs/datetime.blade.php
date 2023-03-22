@php
    $dateInputName = $name . '_date';
    $timeInputName = $name . '_time';

    $dateOptions = [
        'class' => 'form-date form-control',
        'autocomplete' => 'off',
        'placeholder' => trans('backoffice::default.date')
    ] + ($options['date'] ?? []);
    $dateOptions['id'] = $dateOptions['id'] ?? $dateInputName;

    $timeOptions = $options['time'] ?? [];
    $timeOptions['id'] = $timeOptions['id'] ?? $timeInputName;

    unset($options['date'], $options['time']);
@endphp

@if($readonly)
    <p class="form-control-static">{!! $value->format('Y-m-d H:i:s') !!}</p>
@else
    <div class="form-group form-datetime" {!! HTML::attributes($options) !!}>
        <div class="row">
            {!! Form::hidden($name, $value ? $value->format('Y-m-d H:i:s') : '') !!}
            <div class="col-xs-6">
                <div class="input-group">
                    {!! Form::text($dateInputName, $value ? $value->format('Y-m-d') : '', $dateOptions) !!}
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="input-group">
                    @include('backoffice::inputs.time', [
                        'name' => $timeInputName,
                        'label' => trans('backoffice::default.time'),
                        'options' => $timeOptions,
                        'value' => $value ? $value->format('H:i:s') : ''
                    ])
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                </div>
            </div>
        </div>
    </div>
@endif
