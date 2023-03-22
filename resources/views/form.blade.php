<div class="panel panel-default">
	{!! Form::open($formOptions) !!}
	@foreach($inputs->getHidden() as $input)
		{!! $input->render() !!}
	@endforeach
	<div class="panel-heading">
		<h4 class="panel-title">{!! $label !!}</h4>
	</div>
	<div class="panel-body panel-body-nopadding">
		@foreach($inputs->getVisible() as $input)
		<div class="form-group{!! $errors->has($input->name()) || $errors->has($input->dottedName()) ? ' has-error' : '' !!}@if($input->isRequired()) control-required @endif">
			<label for="{!! $input->name() !!}" class="col-sm-3 control-label">
				{!! $input->label() !!}
			</label>
			<div class="col-sm-6">
				{!! $input->render() !!}
				@include('backoffice::inputs.errors.error', ['input' => $input])
			</div>
		</div>
		@endforeach
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3 bottom-actions">
				{!! Form::submit($submitLabel, ['class' => 'btn btn-primary']) !!}
				<a href="{!! $cancelAction !!}" class="btn btn-default js-btn-cancel">{!! Lang::get('backoffice::default.cancel') !!}</a>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>
