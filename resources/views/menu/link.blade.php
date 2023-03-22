<a href="{!! $target !!}"{!! \HTML::attributes($options) !!}>
	@if($icon) {!! icon($icon) !!} @endif
	<span>{!! $label !!}</span>
</a>
