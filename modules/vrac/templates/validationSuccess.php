<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<div id="contenu">
    <div id="rub_contrats" >
        <section id="principal">
        <?php include_partial('headerVrac', array('vrac' => $vrac,'actif' => 4)); ?>        
            <div id="contenu_etape"> 
                <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>">

                    <div id="titre"><span class="style_label">Récapitulatif de la saisie</span></div>

                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>
                    <div id="ligne_btn">
                        <div class="btnAnnulation">
                             <a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_majeur btn_noir"><span>Précédent</span></a>
                        </div>
                        <div class="btnValidation">
                                <span>&nbsp;</span>
                                <button class="btn_majeur btn_etape_suiv" type="submit">Valider</button>
                        </div>      
                    </div>   
                </form>
            </div>
        </section>
        <aside id="colonne">
        <?php
            /*
            * Inclusion du panel de progression d'édition du contrat
            */
            include_partial('contrat_progression', array('vrac' => $vrac));

            /*
            * Inclusion du panel pour les contrats similaires
            */
            include_partial('contratsSimilaires', array('vrac' => $vrac));

            /*
            * Inclusion des Contacts
            */
            include_partial('contrat_infos_contact', array('vrac' => $vrac));

            /*
            * Inclusion de l'aide
            */
            include_partial('contrat_aide', array('vrac' => $vrac));
        ?>
        </aside>
        <?php 
        $params = array('etape' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ETAPE],
                        'vendeur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_VENDEURID],
                        'acheteur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ACHETEURID],
                        'mandataire' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_MANDATAIREID],
                        'produit' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_PRODUIT],
                        'type' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_TYPE],
                        'volume'=>$vrac[VracClient::VRAC_SIMILAIRE_KEY_VOLPROP]);

        $vracs = VracClient::getInstance()->retrieveSimilaryContracts($params);
        if(isset($vracs) && ($vracs!=false) && count($vracs->rows)>0)
            include_partial('contratsSimilaires_warning_popup', array('vrac' => $vrac));
        ?>
    </div>
</div>