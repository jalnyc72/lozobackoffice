@extends("backoffice::emails.layout")

@section('title')
	{{ Lang::get('backoffice::emails.activation.title', ['name' => $name]) }}
@stop

@section('body')
	<p>
		{!! Lang::get('backoffice::emails.activation.text') !!}
		<a href="{{ $link }}">{{ $link }}</a>
	</p>
@stop
