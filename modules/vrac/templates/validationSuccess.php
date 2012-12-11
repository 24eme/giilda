<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<script type="text/javascript">
    $(document).ready(function()
    {
<?php echo ($contratsSimilairesExist) ? 'initValidationWithPopup();' : 'initValidation();'; ?>
        });
</script>

<div id="contenu">
    <div id="rub_contrats" >
        <section id="principal">
            <?php include_partial('headerVrac', array('vrac' => $vrac, 'actif' => 4)); ?>        
            <div id="contenu_etape"> 
                <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation', $vrac) ?>">
                    <?php if ($non_valide) : ?>
                        <div id="titre">
                            <span class="style_label">Ce contrat ne peut pas être validé.</span>
                        </div>
                        <div id="titre">
                            <span>Le volume ou le prix du contrat est manquant.</span>
                        </div>
                    <?php else: ?>
                    <div id="titre"><span class="style_label">Récapitulatif de la saisie</span></div>
                    <?php endif; ?>

                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>

                    <div class="btn_etape">
                        <a href="<?php echo url_for('vrac_condition', $vrac); ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
                        <?php if (!$non_valide) : ?>
                            <a id="btn_validation" class="btn_validation"><span>Terminer la saisie</span></a>  
                        <?php endif; ?>
                    </div> 
                </form>
            </div>
        </section>
        <aside id="colonne">
            <?php include_partial('colonne', array('vrac' => $vrac, 'contratNonSolde' => $contratNonSolde)); ?>
        </aside>       
        <?php
        if ($contratsSimilairesExist)
            include_partial('contratsSimilaires_warning_popup', array('vrac' => $vrac));
        ?>
    </div>
</div>