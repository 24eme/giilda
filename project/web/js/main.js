
/**
 * Initialisation
 ******************************************/

        

        
(function ($)
{
    
    var options = {
        selectors: {
            ajaxModal: '#ajax-modal'
        }
    };

    $(document).ready(function ()
    {
        $(document).initAdvancedElements();

        $.initQueryHash();

        $(options.selectors.ajaxModal).on("show.bs.modal", function (e) {
            var link = $(e.relatedTarget);
            $(this).load(link.attr("href"), function () {
                $(this).initAdvancedElements();
            });
        });
        $(options.selectors.ajaxModal).on("hidden.bs.modal", function (e) {
            $(this).html("");
        });
    });
    
    $.fn.initAdvancedElements = function () {

        var element = $(this);

        $(this).find('.input-float').inputNumberFormat({'decimal': 4, 'decimalAuto': 2});
        $(this).find('.input-integer').inputNumberFormat({'decimal': 0, 'decimalAuto': 0});

        $(this).find("select.select2").select2({
            allowClear: true
        });

        $(this).find(".select2autocomplete").each(function () {
            var urlAjax = $(this).data('ajax');
            var defaultValue = $(this).val();
            var defaultValueSplitted = defaultValue.split(',');
            var select2 = $(this);
            $(this).select2({
                onselected: function () {
                    console.log('fre');
                },
                initSelection: function (element, callback) {
                    if (defaultValue != '') {
                        callback({id: defaultValueSplitted[0], text: defaultValueSplitted[1]});
                        select2.val(defaultValueSplitted[0]);
                    }
                },
                
                placeholder: 'Entrer un nom',
                minimumInputLength: 3,
                formatInputTooShort: function (input, min) { 
                	var n = min - input.length; 
                	return  min + " caractère" + (n == 1 ? "" : "s") + " min";
                },
                formatNoMatches: function () { 
                	return "Aucun résultat"; 
                },
                formatSearching: function () { 
                	return "Recherche…"; 
                },
                allowClear: true,
                ajax: {
                    quietMillis: 150,
                    url: urlAjax,
                    dataType: 'json',
                    type: "GET",
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data) {
                        var results = [];
                        $.each(data, function (index, item) {
                            results.push({
                                id: index,
                                text: item
                            });
                        });
                        return {
                            results: results
                        }

                    }}
            });
        });

        $(this).find(".select2SubmitOnChange").on("change", function(e) {
            if(e.val) {
                $(this).parents('form').submit();
            }
        });

        if ($(this).find('.select2permissifNoAjax').length) {
            var lastValue = null;
            $('.select2permissifNoAjax').select2({
                data: JSON.parse($('input.select2permissifNoAjax').attr('data-choices')),
                multiple: false,
                placeholder: true,
                createSearchChoice: function (term, data) {
                    if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                        return {id: term, text: term + ' (nouveau)'};
                    }
                }
            }).on("select2-close", function () {
                var old_choices = JSON.parse($('input.select2permissifNoAjax').attr('data-choices'));
                old_choices.push({id: lastValue, text: lastValue + ' (nouveau)'});
                $('input.select2permissifNoAjax').select2("val", lastValue);
                $('input.select2permissifNoAjax').val(lastValue);
                $('.select2permissifNoAjax .select2-chosen').text(lastValue);
            }).on("select2-highlight", function (e) {
                lastValue = e.val;
            })
        }
        
        $(this).find('.hamzastyle').each(function() {
            var select2 = $(this);
            select2.select2({
                multiple: true,
                data: function() {
                    var data = [];
                    element.find('.hamzastyle-item').each(function() {
                        data = data.concat(JSON.parse($(this).attr('data-words')));
                    });

                    var data = unique(data.sort());

                    dataFinal = [];
                    for(key in data) {
                        if(data[key]+"") {
                            dataFinal.push({ id: (data[key]+""), text: (data[key]+"") });
                        }
                    }

                    return { results: dataFinal };
                }
            })
        });

        $(this).find('.hamzastyle').on("change", function(e) {
            var select2Data = $(this).select2("data");
            var selectedWords = [];
            for(key in select2Data) {
                selectedWords.push(select2Data[key].text);
            }

            if(!selectedWords.length) {
                document.location.hash = "";
            } else {
                document.location.hash = encodeURI("#filtre=" + JSON.stringify(selectedWords));
            }
        })
        
        $(this).find('.input-group.date').datetimepicker({
            locale: 'fr_FR',
            format: 'L',
            allowInputToggle: true,
            focusOnShow: true,
            useCurrent: false
        });

        $(this).find("form.form-ajax-modal").on('submit', function () {
            var form = $(this);
            var callback = $(this).attr('callback');
            $.post($(this).attr('action'),
                    $(this).serialize(),
                    function (data)
                    {
                        if (!data.success) {
                            var content = form.find(form.attr('data-content')).html(data.content);
                            content.initAdvancedElements();
                            return;
                        }

                        if (form.data('related-element')) {
                            $(form.data('related-element')).trigger("modal_callback", data);
                        }

                        $(options.selectors.ajaxModal).modal('hide');
                    }, "json");

            return false;
        });

        $(this).find('.modal-autoshow').modal({
            'show': true
        });

        $(this).find('.link-submit').on('click', function () {
            var form = $($(this).attr('data-form'));
            form.attr('action', $(this).attr('href'));
            form.submit();

            return false;
        });
        $(this).find('.pointer').on('click', function () {
            if ($(this).attr('data-pointer')) {
                $($(this).attr('data-pointer')).click();
            }

            return false;
        });

        $(this).find('.dynamic-element-add').on('click', function () {
            var content = $($($(this).attr('data-template')).html().replace(/var---nbItem---/g, UUID.generate()));
            $($(this).attr('data-container')).append(content);
            content.initAdvancedElements();
        });

        $(this).find('.dynamic-element-delete').on('click', function () {
            $($(this).attr('data-line')).remove();
            if ($($(this).attr('data-lines')).length < 1 && $(this).attr('data-add')) {
                $($(this).attr('data-add')).trigger('click');
            }
        });


    }

    $.initQueryHash = function () {
        $(window).on('hashchange', function() {
            if($(document).find('.hamzastyle').length) {
                var params = jQuery.parseParams(location.hash.replace("#", ""));
                var filtres = [];
                if(params.filtre && params.filtre.match(/\[/)) {
                    filtres = JSON.parse(params.filtre);
                } else if (params.filtre) {
                    filtres.push(params.filtre);
                }

                var select2Data = [];
                for(key in filtres) {
                    select2Data.push({ id: filtres[key], text: filtres[key] });
                }

                $(document).find('.hamzastyle').select2("data", select2Data);

                $(document).find('.hamzastyle-item').each(function() {
                    var words = $(this).attr('data-words');
                    var find = true;
                    for(key in filtres) {
                        var word = filtres[key];
                        if (words.indexOf(word) === -1) {
                            find = false;
                        }
                    }
                    if (find) {     
                        $(this).show();    
                    } else {
                        $(this).hide();    
                    }
                });
            }
            if($(document).find('.nav.nav-tabs').length) {
                var params = jQuery.parseParams(location.hash.replace("#", ""));
                if(params.tab) {
                    $('.nav.nav-tabs a[aria-controls="'+params.tab+'"]').tab('show');
                }
            }
        });

        if(location.hash) {
            $(window).trigger('hashchange');
        }
    }

})(jQuery);