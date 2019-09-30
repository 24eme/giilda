$(document).ready(function()
{
    var url_all = '/societe-etablissement-compte/autocomplete/INTERPRO-inter-loire/tous';
    var url_actif = '/societe-etablissement-compte/autocomplete/INTERPRO-inter-loire/actif';
    $('select#contacts_identifiant').attr('data-ajax',url_actif);
    $('#contacts_all').change(function() {
        if($('#contacts_all:checked').val()==1){
            $('select#contacts_identifiant').attr('data-ajax',url_all);
        }else{
            $('select#contacts_identifiant').attr('data-ajax',url_actif);
        }
    });

    $('#societe_modification_type_numero_compte_fournisseur_FOURNISSEUR').change(function(){
       if($(this).is(':checked')) $('input[name="societe_modification[type_fournisseur][]"]').removeAttr('disabled');
       else $('input[name="societe_modification[type_fournisseur][]"]').attr('disabled','disabled');
    });

    });
