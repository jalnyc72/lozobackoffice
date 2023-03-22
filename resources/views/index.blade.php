@extends('backoffice::layouts.default')

@if(isset($title))
	@section('head.title', $title)

    @section('titlebar.title')
        {!! $title !!}
    @endsection
@endif

@if(isset($breadcrumb))
	@section('titlebar.breadcrumb')
        {!! $breadcrumb !!}
    @endsection
@endif

@section('panel.content')
	{!! $list->render() !!}
@stop
