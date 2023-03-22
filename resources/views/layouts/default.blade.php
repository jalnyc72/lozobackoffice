@extends('backoffice::layouts.empty')

@section('body.navigation')
	@include('backoffice::menu')
@stop

@if(Cookie::get('leftpanel-collapsed'))
	@section('body.class', 'leftpanel-collapsed')
@endif

@section('body.content')
<div class="leftpanel">
	@section('body.logo')
		@include('backoffice::layouts.partials.logo')
	@show
	<div class="leftpanelinner">
		@yield('body.navigation')
	</div>
</div>
<div class="mainpanel">
	@section('body.header')
	<div class="headerbar">
		<a class="menutoggle"><i class="fa fa-bars"></i></a>
		<div class="header-right">
			@include('backoffice::auth.partials.menu')
		</div>
	</div>
	@show
	<div class="pageheader">
		<h2>@yield('titlebar.title')</h2>
		@yield('titlebar.actions')
		<div class="breadcrumb-wrapper">
			@yield('titlebar.breadcrumb')
		</div>
	</div>
	<div class="contentpanel">
		@yield('panel.content')
	</div>
</div>
@stop
