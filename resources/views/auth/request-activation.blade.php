@extends('backoffice::layouts.empty')

@section('body.class', 'signin')

@section('body.content')
<div class="signinpanel">
	<div class="row">
		<div class="col-md-7">
			@include('backoffice::auth.partials.signin-info')
		</div>

		<div class="col-md-5">
			{!! Form::open(['route' => "$contextKey.auth.resend_activation_request", 'role' => 'form']) !!}
				<h4 class="nomargin">{!! Lang::get('backoffice::auth.activation.title') !!}</h4>
				<p class="mt5 mb20">{!! Lang::get('backoffice::auth.activation.confirm') !!}</p>

				<div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
					{!! Form::text('email', old('email'), ['class' => 'form-control uname', 'placeholder' => Lang::get('backoffice::auth.email')]) !!}
					@if ($errors->has('email'))
						@foreach ($errors->get('email') as $error)
							<label for="email" class="error">{{ $error }}</label>
						@endforeach
					@endif
				</div>
                <button type="submit" class="btn btn-warning btn-block">{!! Lang::get('backoffice::auth.activation.send-email') !!}</button>
			{!! Form::close() !!}
		</div>
	</div>

	<div class="signup-footer">
		<div class="pull-left">
			{!! Lang::get('backoffice::auth.copyright') !!}
		</div>
	</div>
</div>
@stop
