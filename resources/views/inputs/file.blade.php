@if($readonly)
    <p class="form-control-static">{!! $value !!}</p>
@else
    <div class="input-group input-group-file">
        <input type="text" class="form-control" readonly>
        <label class="input-group-btn">
            <span class="btn btn-default">
                {{ trans('backoffice::default.search') }}&hellip;
                {!! Form::file($name, array_add($options, 'style', 'display:none')) !!}
            </span>
        </label>
    </div>
@endif