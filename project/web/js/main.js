
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
        $(this).find(".select2").select2({
            allowClear: true
        });
        $(this).find(".select2autocomplete").each(function () {
            var urlAjax = $(this).data('ajax');
            $(this).select2({
                placeholder: 'Enter name',
                minimumInputLength: 3,
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

                    }}});
        });
        $(this).find('.input-group.date').datetimepicker({
            locale: 'fr_FR',
            format: 'L',
            allowInputToggle: true,
            focusOnShow: true,
            useCurrent: false
        });
        $(this).find("form.form-ajax-modal").on('submit', function () {
            var form = $(this);
            $.post($(this).attr('action'),
                    $(this).serialize(),
                    function (data)
                    {
                        if (!data.success) {
                            var content = form.find(form.attr('data-content')).html(data.content);
                            content.initAdvancedElements();
                            return;
                        }

                        $(options.selectors.ajaxModal).modal('hide');
                    }, "json");
            return false;
        });

        $(this).find('.modal-autoshow').modal({
            'show': true
        });

        $(this).find('.link-submit').on('click', function() {
            var form = $($(this).attr('data-form'));
            form.attr('action', $(this).attr('href'));
            form.submit();
            
            return false;
        });
        $(this).find('.pointer').on('click', function() {
            if($(this).attr('data-pointer')) {
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

})(jQuery);