$(document).ready(function()
{
        var url_all = '/vinsdeloire_dev.php/societe-etablissement-compte/autocomplete/INTERPRO-inter-loire/tous';
    var url_actif = '/vinsdeloire_dev.php/societe-etablissement-compte/autocomplete/INTERPRO-inter-loire/actif';
    $('select#contacts_identifiant').attr('data-ajax',url_actif);
    $('#contacts_all').change(function() {
        if($('#contacts_all:checked').val()==1){
            $('select#contacts_identifiant').attr('data-ajax',url_all);
        }else{
            $('select#contacts_identifiant').attr('data-ajax',url_actif);
        }
    });

});