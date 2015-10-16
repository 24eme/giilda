/* =================================================================================== */
/* JQUERY CONTEXT */
/* =================================================================================== */
(function($)
{
    var _doc = $(document);

    _doc.ready(function()
    {
        $('.data-sum-element').change(function() {
            try {
                var elements = JSON.parse($(this).attr('data-sum-element'));
            } catch (e) {

            }

            if(!elements) {
                sumElement($($(this).attr('data-sum-element')));
                return;
            }

            for(keyElement in elements) {
                sumElement($(elements[keyElement]));
            }
        });

        $('.detail input').keypress(function() {
            stateDetail($($(this).attr('data-detail')));
        });

        $('.data-clean-line').click(function() {
            if(!confirm("Étes-vous sur de vouloir supprimer cette ligne ?")) {
                return;
            }
            cleanDetail($($(this).attr('data-detail')));
        });

        $('.data-add-line').click(function() {
            var form = $($(this).attr('data-form'));
            form.attr('action', $(this).attr('data-form-action'));
            form.submit();
        });

//        $('.detail').hover(function() {
//            if($(this).hasClass('empty')) {
//                return;
//            }
//            $(this).find('button').removeClass("hidden");
//        }, function() {
//            $(this).find('button').addClass("hidden");
//        });
    });

    var stateLine = function(groupLine) {
        var line = groupLine.find('.line');
        if(groupLine.find('.detail.empty').length == groupLine.find('.detail').length) {
            line.addClass('empty');
            line.css('opacity', '0.5');
        } else {
            line.removeClass('empty');
            line.css('opacity', '1');
        }
    }

    var cleanDetail = function(detail) {
        detail.find('input').each(function() {
            $(this).val(null);
            $(this).change();
        });
        stateDetail(detail);
    }
   
    var stateDetail = function(detail) {
        var empty = true;
        detail.find('input').each(function() {
            if($(this).val()) {
                empty = false;
            }
        });

        if(empty) {
            detail.css('opacity', '0.5');
            detail.addClass('empty');
        } else {
            detail.css('opacity', '1');
            detail.removeClass('empty');
            detail.find('button').removeClass("hidden");
        }

        stateLine($(detail.attr('data-line')));
    }

    var sumElement = function(element) {
        var sum = 0;
        try {
            var expression = element.attr('data-sum').replace(/(#[0-9a-zA-Z_-]+)/g, 'parseFloat(($("$1").val()) ? $("$1").val() : 0)');
            sum = eval(element.attr('data-sum').replace(/(#[0-9a-zA-Z_-]+)/g, 'parseFloat(($("$1").val()) ? $("$1").val() : 0)'));
        } catch (e) {

        }

        element.val(Math.round(sum * 100) / 100);

        element.change();
    }
})(jQuery);