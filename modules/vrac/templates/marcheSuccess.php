<?php
/* Fichier : marcheSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/marche
 * Formulaire d'enregistrement de la partie marche d'un contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : ${date}
 */
$contratNonSolde = ((!is_null($form->getObject()->valide->statut)) && ($form->getObject()->valide->statut!=VracClient::STATUS_CONTRAT_SOLDE));
?>
<script type="text/javascript">
    $(document).ready(function()
    {
       var ajaxParams = { 'numero_contrat' : '<?php echo $form->getObject()->numero_contrat ?>',
                          'vendeur' : '<?php echo $form->getObject()->vendeur_identifiant ?>',
                          'acheteur' : '<?php echo $form->getObject()->acheteur_identifiant ?>',
                          'mandataire' : '<?php echo $form->getObject()->mandataire_identifiant ?>' };
                      
       $('#produit input').live( "autocompleteselect", function(event, ui)
       {
           
           var integrite = getContratSimilaireParams(ajaxParams,ui);
           refreshContratsSimilaire(integrite,ajaxParams);
                
       });
       
       $('#volume_total').change(function()
       {
           var integrite = getContratSimilaireParams(ajaxParams,null);
           refreshContratsSimilaire(integrite,ajaxParams);     
       });
       
       $('#type_transaction input').change(function()
       {
           var integrite = getContratSimilaireParams(ajaxParams,null);
           refreshContratsSimilaire(integrite,ajaxParams);                 
       });
       
       var refreshContratsSimilaire = function(integrite,ajaxParams) 
       {
        if(integrite) 
        {
            $.get('getContratsSimilaires',ajaxParams,
                function(data)
                {
                    $('#contrats_similaires').html(data);
                });               
        }      
       }
           
       
       var getContratSimilaireParams = function(ajaxParams,ui)
       {
           var type = $('#type_transaction input:checked').val();
           if(type=='') return false;
           ajaxParams['type'] = type;
           
           if(ui==null){
               ajaxParams['produit'] = $('#produit option:selected').val();
           }
           else{
               ajaxParams['produit'] = ui.item.option.value;
           }
           
           var volume = $('#volume_total').val();
           if((volume!='') && (ajaxParams['produit']=='')) return false;
           ajaxParams['volume'] = volume;
           
           return true;
       }
       //ajax_send_contrats_similairesMarche('<?php //echo $form->getObject()->numero_contrat ?>');
    });
</script>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 2)); ?>
            <div id="contenu_etape">  
                <form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>">    
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                <div id="marche">

                <!--  Affichage des l'option original  -->
                    <div id="original" class="original section_label_strong">
                        <?php echo $form['original']->renderLabel() ?>
                        <?php echo $form['original']->render() ?>        
                        <?php echo $form['original']->renderError(); ?>
                    </div>

                    <!--  Affichage des transactions disponibles  -->
                    <div id="type_transaction" class="type_transaction section_label_maj">
                        <?php echo $form['type_transaction']->renderLabel() ?>
                        <?php echo $form['type_transaction']->renderError(); ?>                    
                        <?php echo $form['type_transaction']->render() ?>        
                    </div>

                <!--  Affichage des produits, des labels et du stock disponible  -->
                    <div id="vrac_marche_produitLabel" class="section_label_maj">
                        <?php include_partial('marche_produitLabel', array('form' => $form)); ?>
                    </div>

                <!--  Affichage des volumes et des prix correspondant  -->
                    <div id="vrac_marche_volumePrix" class="section_label_maj">
                        <?php include_partial('marche_volumePrix', array('form' => $form)); ?>
                    </div>

                </div>
                <div id="ligne_btn">
                    <div class="btnAnnulation">
                          <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_majeur btn_noir"><span>Précédent</span></a>
                    </div>
                    <div class="btnValidation">
                        <span>&nbsp;</span>
                            <button class="btn_majeur btn_etape_suiv" type="submit">Etape Suivante</button>
                    </div>      
                </div>
            </form>
            </div>      
        </div>
        <aside id="colonne">
        <?php include_partial('colonne', array('vrac' => $form->getObject(),'contratNonSolde' => $contratNonSolde)); ?>
        </aside>
</div>
                    
    
