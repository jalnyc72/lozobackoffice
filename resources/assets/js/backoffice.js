jQuery.migrateMute = true;
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _CSRF_TOKEN
        }
    });

    bootbox.setDefaults("locale", document.documentElement.getAttribute('lang') || 'en');

    var $selects = $('select');
    // Select2
    $selects.filter(":not(.multiselect)").each(function(){
        $(this).select2({
            width: "resolve",
            allowClear: true,
            closeOnSelect: $(this).attr('multiple')
        });

        // $(this).select2('readonly', $(this).is('[readonly]'));
    });

    $('select.sortable').each(function() {
        var $select2 = $(this).select2({
            width: "resolve",
            allowClear: true,
            closeOnSelect: false,
            containerCssClass: 'select2-sortable'
        }).select2Sortable();
    });

    // Delete row in a table
    $('.delete-row').click(function(){
        var theForm = $(this).parents('form').first();

        bootbox.confirm("Are you sure you want to delete this?", function(result) {
            if (result) {
                theForm.trigger('submit');
            }
        });

        return false;
    }).each(function(){
        $(this).parents('form').ajaxForm({
            success: function(responseText, statusText, xhr, $form){
                $form.closest('tr').fadeOut(function(){
                    $(this).remove();
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                var response = jqXHR.responseJSON;

                jQuery.gritter.add({
                    title: response.title || 'Error',
                    text: response.message,
                    class_name: 'growl-danger'
                });
            }
        });
    });

    // Show action upon row hover
    $('.table-hidaction tbody tr').hover(function(){
        $(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
        $(this).find('.table-action-hide a').animate({opacity: 0});
    });

    // --- copied from search-results.js
    // Basic Slider
    if ($('#slider').length) {
        $('#slider').slider({
            range: "min",
            max: 100,
            value: 50
        });
    }

    // Date Picker
    if ($('.form-date').length){
        $('.form-date').each(function(){
            var dataOptions = {};
            $.each(this.dataset, function(k, v){
                dataOptions[$.camelCase(k)] = v;
            });

            var options = $.extend({}, {
                dateFormat: 'yy-mm-dd',
                onSelect: function(date){
                    $(this).trigger('changeDate', date);
                }
            }, dataOptions);
            $(this).datepicker(options);
        });
    }

    // Time Picker
    if ($('.form-time').length){
        $('.form-time').each(function(){
            $(this).timepicker({
                defaultTime: false,
                showMeridian: false,
                showSeconds: true,
                minuteStep: 5,
                secondStep: 5,
                disableFocus: true
            });

            $(this).on('focus', function(){
                return $(this).timepicker('showWidget');
            });
        });
    }

    // Combined datetime picker
    $('.form-datetime').each(function(){
        var
            $date   = $('.form-date', this),
            $time   = $('.form-time', this),
            $hidden = $('input[type=hidden]', this),
            updateHidden = function(date, time){
                $hidden.val(date + ' ' + time);
            };

        $('.form-date', this).on('changeDate', function(event, date){
            updateHidden(date, $time.val());
        });
        $('.form-time', this).on('changeTime.timepicker', function(event){
            updateHidden($date.val(), event.time.value);
        });
    });

    // Copied from form-validation.js (edited as well)

    // Basic Form
    $("form").validate({
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        }
    });

    $('.shoutMe').each(function(){
        jQuery.gritter.add({
            title:      $(this).data('title') || 'Message',
            text:       $(this).text(),
            class_name: $(this).data('class_name'),
            image: $(this).data('image'),
            sticky: $(this).data('sticky') === 'true',
            time: $(this).data('time') || ''
        });
    });

    $('.wysiwyg').wysihtml5({
        toolbar: {
            "font-styles": false,
            "emphasis": true,
            "lists": true,
            "html": false,
            "link": true,
            "image": true,
            "color": false,
            "blockquote": true,
            "fa": true,
        },
        autoLink: true,
        locale: document.documentElement.getAttribute('lang') === 'es' ? 'es-AR' : 'en',
        parserRules: {
            tags: {
                strong: {},
                b:      {},
                i:      {},
                em:     {},
                br:     {},
                p:      {},
                div:    {},
                span:   {},
                ul:     {},
                ol:     {},
                li:     {},
                a: {
                    set_attributes: {
                        target: "_blank",
                        rel:    "nofollow"
                    },
                    check_attributes: {
                        href:   "url" // important to avoid XSS
                    }
                }
            }
        },
        stylesheets: []
    });

    $selects.filter('.multiselect').each(function(){
        $(this).multiSelect({
            selectableHeader: '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="' + ($(this).attr('placeholder') || '') + '">',
            selectionHeader:  '<input type="text" class="search-input form-control mb5" autocomplete="off" placeholder="' + ($(this).attr('placeholder') || '') + '">',
            selectableOptgroup: true,
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-selectable ul.ms-list > li:visible',
                    selectionSearchString  = '#' + that.$container.attr('id') + ' .ms-selection ul.ms-list > li:visible';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 13){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 13){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    });

    $(document).on('click', '#show-more-link', function(ev) {
        var $obj = $(this);

        $('.filter-advance').toggle();

        var new_text = $obj.data('text'),
            old_text = $obj.text();

        $obj.text(new_text);
        $obj.data('text', old_text);
    });
    $(document).on('change', 'input.chk-all', function(){
        $('input.chk-bulk').attr('checked', $(this).is(':checked'));

        if ($(this).is(':checked'))
        {
            $('.actions-bulk').show();
        }
        else
        {
            $('.actions-bulk').hide();
        }
    });

    $(document).on('change', 'input.chk-bulk', function(){
        var $bulkInputs = $('input.chk-bulk');

        var total   = $bulkInputs.length,
            checked = $bulkInputs.filter(':checked').length,
            $all    = $('input.chk-all');

        $all.get(0).indeterminate = (checked > 0 && checked != total);

        if (checked == total)
        {
            $all.attr('checked', 'checked');
        }
        else
        {
            $all.attr('checked', null);
        }

        if (checked > 0)
        {
            $('.actions-bulk').show();
        }
        else
        {
            $('.actions-bulk').hide();
        }
    });

    $('.actions-bulk form').each(function(){
        $(this).on('submit', function(){
            var $self = $(this);
            $self.find('.bulk-row').remove();

            $('.chk-bulk:checked').each(function(){
                var $hidden = $('<input type="hidden">');
                $hidden.attr('class', 'bulk-row');
                $hidden.attr('name', 'row[]');
                $hidden.val($(this).val());

                $self.append($hidden);
            });
        });
    });
    var menuState = new function(){
        var collapsed = Cookies.get('leftpanel-collapsed') != undefined,
            collapse = function(){
                if (!collapsed) {
                    Cookies.set('leftpanel-collapsed', 'leftpanel-collapsed');
                    collapsed = !collapsed;
                }
            },
            expand = function(){
                if (collapsed) {
                    Cookies.remove('leftpanel-collapsed');
                    collapsed = !collapsed;
                }
            };

        this.toggle = function(){
            if (collapsed) {
                expand();
            } else {
                collapse();
            }
        }
    };

    $(document).on('click', '.menutoggle', function(){
        menuState.toggle();
    });

    if(window.matchMedia('(min-width: 992px)').matches) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Hook up on any button that needs confirmation and alert the user
    $('button[data-confirm]').click(function(){
        var theForm = $(this).parents('form').first(),
            message = $(this).data('confirm');

        bootbox.confirm(message, function(result) {
            if (result) {
                theForm.trigger('submit');
            }
        });

        return false;
    });

    // ColorPicker
    $('.colorpicker-container').each(function(){
        var $input = $('input[type=text]', this);
        if ($input.length) {
          $input.colorpicker({
            regional: document.documentElement.getAttribute('lang') || 'en',
            showOn: 'both',
            buttonColorize: true,
            buttonImage: '/vendor/backoffice/images/colorpicker/ui-colorpicker.png',
            buttonImageOnly: true,
            colorFormat: '#HEX',
            init: function(){
                setTimeout(function(){
                  var button = $input.next();
                  $input.after($('<span class="input-group-addon"></span>').append(button));
                });
            }
          });
        }
    });

    // Cancel buttons on modal close modals instead
    $('.modal .js-btn-cancel').each(function(){
        $(this).attr('data-dismiss', 'modal');
    });

    $('.modal').on('hide.bs.modal', function(){
        $('.has-error').removeClass('has-error');
        $('label.error').remove();
    });

    $('[data-nested-parent]').each(function () {
        var $child = $(this);
        var $parent = $('#' + $child.data('nested-parent'));
        var route = $child.data('nested-route');
        var selected = $child.data('nested-value');

        $parent.on("change", function () {
            $child.html($child.find("option:eq(0)")[0]).trigger('change');

            if ($parent.val() === '') {
                return false;
            }

            $.ajax(route + '?parent=' + $parent.val(), {
                dataType: "json",
                success: function(data) {
                    for (var key in data) {
                        var shouldBeSelected = data[key].id == selected;

                        $child.append(new Option(data[key].text, data[key].id, shouldBeSelected, shouldBeSelected));
                    }
                    $child.trigger('change');
                }
            });
        }).trigger('change');
    });

    $('[data-tagger]').each(function () {
        var data = $(this).data('options');

        $(this).select2({
            tags: data,
            tokenSeparators: [","],
            multiple: true,
            closeOnSelect: false,
            allowClear: false,
            width: 'resolve',
            initSelection : function (element, callback) {
                var data = [];
                $(element.val().split(",")).each(function () {
                    data.push({id: this, text: this.replace(/^_+|_+$/g, '')});
                });
                callback(data);
            },
            createSearchChoice: function (tag) {
                var found = false;
                for(var i = 0; i < data.length; i++)
                {
                    if ($.trim(tag).toUpperCase() === $.trim(data[i].text).toUpperCase())
                    {
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    return {
                        id: '_' + tag + '_',
                        text: tag,
                        isNew: true
                    };
                }
            }
        });
    });

    $('[data-suggest]').each(function () {
        var input = $(this);
        var inputId = input.attr('id');

        input.select2({
            multiple: !!input.attr('multiple'),
            placeholder: input.data('placeholder'),
            formatNoMatches: input.data('format-no-matches'),
            formatSearching: input.data('format-searching'),
            formatInputTooShort: input.data('format-input-too-short'),
            minimumInputLength: input.data('minimum-input-length'),
            allowClear: true,
            ajax: {
                url: input.data('url'),
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return { term: term };
                },
                results: function (data, page) {
                    return { results: data };
                },
                cache: true
            }
        })
        .on("change", function(e) {
            $('[name="' + inputId + '_json"]').val(JSON.stringify(input.select2('data')));
        });

        try {
            input.select2('data', JSON.parse($('[name="' + inputId + '_json"]').val()))
        } catch(e) {}

        if(input.data('sortable')) {
            input.select2("container").find("ul.select2-choices").sortable({
                containment: 'parent',
                start: function() { input.select2("onSortStart"); },
                update: function() { input.select2("onSortEnd"); }
            });
        }
    });

    //File Input
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(':file').on('fileselect', function (event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text'),
            log = label;
        if(numFiles > 1) {
            log += " (+" + (numFiles - 1) + ")";
        }

        if (input.length) {
            input.val(log);
        }
    });
});
