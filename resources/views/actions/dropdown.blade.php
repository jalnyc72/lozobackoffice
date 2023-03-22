<button type="button" data-toggle="dropdown" {!! HTML::attributes($options) !!}>
	{!! $label !!} <span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
	@foreach($actions as $action)
	<li>{!! $action->render() !!}</li>
	@endforeach
</ul>
