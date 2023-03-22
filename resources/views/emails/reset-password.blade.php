@extends("backoffice::emails.layout")

@section('title')
	{{ Lang::get('backoffice::emails.reset-password.title', ['name' => $name]) }}
@stop

@section('body')
	<p>
		{!! Lang::get('backoffice::emails.reset-password.text') !!}
		<a href="{{ $link }}">{{ $link }}</a>
	</p>
@stop