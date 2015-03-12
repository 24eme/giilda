var initDatepickerProduits = function() {
        $(".produits_datepicker input").datepicker({
        showOn: "button",
        buttonImage: "/images/pictos/pi_calendrier.png",
        buttonImageOnly: true,
        dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
        monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
        dateFormat: 'dd/mm/yy'
    });
};

$(document).ready(function()
{
    initDatepickerProduits();
});
