@if($readonly)
<p class="form-control-static">{!! $value !!}</p>
@else
{!! Form::input('number', $name, $value, $options) !!}
@endif

