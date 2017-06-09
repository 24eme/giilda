<?php
echo $societeForm->renderHiddenFields();
echo $societeForm->renderGlobalErrors();
?>
<div class="form_contenu">
    <div class="form_ligne">
                <label for="type_societe">Type de la société</label>
                <span class="champ_long"><?php echo $societeForm->getObject()->type_societe; ?></span>
    </div>
    <div class="form_ligne">
        <?php echo $societeForm['raison_sociale']->renderError(); ?>
        <?php echo $societeForm['raison_sociale']->renderLabel(); ?>
        <?php echo $societeForm['raison_sociale']->render(array('class' => 'champ_long')); ?>
    </div>
    <div class="form_ligne">
		<div class="form_colonne">
			<?php echo $societeForm['raison_sociale_abregee']->renderLabel(); ?>
			<?php echo $societeForm['raison_sociale_abregee']->render(); ?>
			<?php echo $societeForm['raison_sociale_abregee']->renderError(); ?>
		</div>
		<div class="form_colonne">
			<?php echo $societeForm['statut']->renderLabel('',array('class' => 'label_liste')); ?>
			<?php echo $societeForm['statut']->render(); ?>
			<?php echo $societeForm['statut']->renderError(); ?>
		</div>
    </div>
    <?php if ($societeForm->getObject()->isNegoOrViti()) : ?>
        <div class="form_ligne">
            <?php echo $societeForm['cooperative']->renderLabel(null,array('class' => 'label_liste')); ?>
            <?php echo $societeForm['cooperative']->render(); ?>
            <?php echo $societeForm['cooperative']->renderError(); ?>
        </div>
    <?php endif; ?>
    <div class="form_ligne">
            <?php echo $societeForm['type_numero_compte_fournisseur']->renderLabel('',array('class' => 'label_liste')); ?>
            <?php if ($societeForm->getObject()->isNegoOrViti()) : ?>
                <?php echo $societeForm['type_numero_compte_client']->render(); ?>
                <?php echo $societeForm['type_numero_compte_client']->renderError(); ?>
            <?php endif; ?>
            <?php echo $societeForm['type_numero_compte_fournisseur']->render(); ?>
            <?php echo $societeForm['type_numero_compte_fournisseur']->renderError(); ?>
        </div>
         <div class="form_ligne">
            <?php echo $societeForm['type_fournisseur']->renderLabel(null,array('class' => 'label_liste')); ?>
            <?php echo $societeForm['type_fournisseur']->render(); ?>
            <?php echo $societeForm['type_fournisseur']->renderError(); ?>
             </div>
        <div class="form_ligne">
			<div class="form_colonne">
				<?php echo $societeForm['siret']->renderLabel(); ?>
				<?php echo $societeForm['siret']->render(); ?>
				<?php echo $societeForm['siret']->renderError(); ?>
			</div>
			<div class="form_colonne">
				<?php echo $societeForm['code_naf']->renderLabel(); ?>
				<?php echo $societeForm['code_naf']->render(); ?>
				<?php echo $societeForm['code_naf']->renderError(); ?>
			</div>
        </div>
        <div class="form_ligne">
            <?php echo $societeForm['no_tva_intracommunautaire']->renderLabel(); ?>
            <?php echo $societeForm['no_tva_intracommunautaire']->render(); ?>
            <?php echo $societeForm['no_tva_intracommunautaire']->renderError(); ?>
        </div>

        <div id="enseignes_list">
            <?php
            foreach ($societeForm['enseignes'] as $enseigneForm) {
                include_partial('itemEnseigne', array('form' => $enseigneForm));
            }
            ?>
            <div class="form_ligne">
                <a class="btn_ajouter_ligne_template" data-container="#enseignes_list" data-template="#template_non_appurement" href="#">Ajouter une enseigne</a>
            </div>
        </div>
        <div class="form_ligne">
            <?php echo $societeForm['paiement_douane_moyen']->renderLabel(null,array('class' => 'label_liste')); ?>
            <?php echo $societeForm['paiement_douane_moyen']->render(); ?>
            <?php echo $societeForm['paiement_douane_moyen']->renderError(); ?>
        </div>
        <div class="form_ligne">
            <?php echo $societeForm['paiement_douane_frequence']->renderLabel(null,array('class' => 'label_liste')); ?>
            <?php echo $societeForm['paiement_douane_frequence']->render(); ?>
            <?php echo $societeForm['paiement_douane_frequence']->renderError(); ?>
        </div>
    <div class="form_ligne">
        <?php echo $societeForm['commentaire']->renderLabel(); ?>
        <?php echo $societeForm['commentaire']->render(); ?>
        <?php echo $societeForm['commentaire']->renderError(); ?>
    </div>
</div>
<?php include_partial('templateEnseigneItem', array('form' => $societeForm->getFormTemplate()));
?>

<script type="text/javascript">

    (function($)
    {

        $(document).ready(function()
        {
            initCollectionAddTemplate('.btn_ajouter_ligne_template', /var---nbItem---/g, callbackAddTemplate);
            initCollectionDeleteTemplate();
        });

        var callbackAddTemplate = function(bloc)
        {

        }


        var initCollectionAddTemplate = function(element, regexp_replace, callback)
        {

            $(element).live('click', function()
            {
                var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate());

                try {
                    var params = jQuery.parseJSON($(this).attr('data-template-params'));
                } catch (err) {

                }

                for(key in params) {
                    bloc_html = bloc_html.replace(new RegExp(key, "g"), params[key]);
                }

                var bloc = $($(this).attr('data-container')).before(bloc_html);

                if(callback) {
                    callback(bloc);
                }
                return false;
            });
        }

        var initCollectionDeleteTemplate = function()
        {
            $('.btn_supprimer_ligne_template').live('click',function()
            {
                var element = $(this).attr('data-container');
                $(this).parent(element).remove();

                return false;
            });
        }
    })(jQuery);



</script>
