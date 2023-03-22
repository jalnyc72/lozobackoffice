<div class="panel panel-default">
    {!! Form::open($formOptions) !!}
    @foreach($inputs->getHidden() as $input)
        {!! $input->render() !!}
    @endforeach
    <div class="panel-heading">
        <h4 class="panel-title">{!! $label !!}</h4>
    </div>
    <div class="panel-body panel-body-nopadding">
        <div class="basic-wizard">
            <ul class="nav nav-pills nav-justified">
                @foreach($languages as $language => $label)
                <li @if($label == reset($languages))class="active"@endif>
                    <a href="#{{ $language }}" data-toggle="tab">
                        {{ $label }}
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($languages as $language => $label)
                    @php($isTranslation = ($label != reset($languages)))
                    <div class="tab-pane @if(!$isTranslation) active @endif" id="{{ $language }}">
                        @include('backoffice::forms.form-body', ['inputs' => $inputs, 'language' => $language, 'isTranslation' => $isTranslation, 'except' => $except])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                {!! Form::submit($submitLabel, ['class' => 'btn btn-primary']) !!}
                <a href="{!! $cancelAction !!}" class="btn btn-default js-btn-cancel">{!! Lang::get('backoffice::default.cancel') !!}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@section('body.javascripts')
    @parent
<script type="text/javascript">
    (function($){
        $(function(){
            $('.js-trans').each(function(){
                var $self = $(this);

                $(document).on('keyup', '[name="' + $self.data('rel') + '"]', function(){
                    $self.html($(this).val().replace(/\n/g,"<br>"));
                });
            });
        });
    })(jQuery);
</script>
@stop