<div class="colorpicker-container">
	@if($readonly)
		<p class="form-control-static">
			{!! $value !!} <span class="preview"{!! $value ? " style=\"background-color: $value;\"" : '' !!}></span>
		</p>
	@else
		<div class="input-group">
			{!! Form::text($name, $value, $options) !!}
		</div>
	@endif
</div>
