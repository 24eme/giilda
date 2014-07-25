

var initSignaturePopup = function() {

    $('#signature_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
    $('#signature_popup').click();
};

$(document).ready(function()
{
    initSignaturePopup();    
});