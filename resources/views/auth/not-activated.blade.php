@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			<p>{{ Lang::get('backoffice::auth.activation.missing.title') }}</p>
			<p>{!! Lang::get('backoffice::auth.activation.missing.message', ['link' => route("$contextKey.auth.login")])  !!}</p>

			<a href="{{ route("$contextKey.auth.resend_activation") }}">{{ Lang::get('backoffice::auth.activation.title') }}</a>
		</div>
	</div>
	<div class="signup-footer">
		<div class="pull-left">
			{!! Lang::get('backoffice::auth.copyright') !!}
		</div>
	</div>
</div>
@stop
