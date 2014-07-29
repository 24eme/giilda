

var initSignaturePopup = function() {

    $('a.signature_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
};

var triggerSignaturePopup = function() {
    $('a.signature_popup').click();
}

$(document).ready(function()
{
    initSignaturePopup();    
});

