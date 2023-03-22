@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>
		<div class="col-md-5">
			<h2>{{ trans('backoffice::auth.throttling.title')}}</h2>
			<p>{{ $message }}</p>
		</div>
	</div>
	<div class="signup-footer">
		<div class="pull-left">
			{!! Lang::get('backoffice::auth.copyright') !!}
		</div>
	</div>
</div>
@stop

