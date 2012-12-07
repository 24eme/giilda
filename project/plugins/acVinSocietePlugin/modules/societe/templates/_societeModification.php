
<?php
echo $societeForm->renderHiddenFields();
echo $societeForm->renderGlobalErrors();
?>
<div class="form_contenu">
    <div class="form_ligne">
        <?php echo $societeForm['raison_sociale']->renderError(); ?>
        <?php echo $societeForm['raison_sociale']->renderLabel(); ?>
        <?php echo $societeForm['raison_sociale']->render(); ?>
    </div>
    <div class="form_ligne">
        <?php echo $societeForm['raison_sociale_abregee']->renderLabel(); ?>
        <?php echo $societeForm['raison_sociale_abregee']->render(); ?>
        <?php echo $societeForm['raison_sociale_abregee']->renderError(); ?>
    </div>
    <div class="form_ligne">
        <?php echo $societeForm['statut']->renderLabel(); ?>
        <?php echo $societeForm['statut']->render(); ?>
        <?php echo $societeForm['statut']->renderError(); ?>
    </div>
    <?php if ($societeForm->getIsOperateur()) : ?>
        <div class="form_ligne">
            <?php echo $societeForm['cooperative']->renderLabel(); ?>
            <?php echo $societeForm['cooperative']->render(); ?>
            <?php echo $societeForm['cooperative']->renderError(); ?>
        </div>
    <?php endif; ?>
    <!--         <div class="form_ligne">
    <label for="type_societe">
    <?php //echo $societeForm['type_societe']->renderLabel(); ?>
    </label>
    <?php //echo $societeForm['type_societe']->render(); ?>
    <?php //echo $societeForm['type_societe']->renderError(); ?>
    </div>                -->
    <div class="form_ligne">
        <?php echo $societeForm['type_numero_compte']->renderLabel(); ?>
        <?php echo $societeForm['type_numero_compte']->render(); ?>
        <?php echo $societeForm['type_numero_compte']->renderError(); ?>
    </div>                 
    <div class="form_ligne">
        <?php echo $societeForm['siret']->renderLabel(); ?>
        <?php echo $societeForm['siret']->render(); ?>
        <?php echo $societeForm['siret']->renderError(); ?>
    </div>                
    <div class="form_ligne">
        <?php echo $societeForm['code_naf']->renderLabel(); ?>
        <?php echo $societeForm['code_naf']->render(); ?>
        <?php echo $societeForm['code_naf']->renderError(); ?>
    </div>
    <div id="enseignes_list">
    <?php
    foreach ($societeForm['enseignes'] as $enseigneForm){
            include_partial('itemEnseigne',array('form' => $enseigneForm));
    }
    ?>
        <div class="form_ligne">
            <a class="btn_ajouter_ligne_template" data-container="#enseignes_list" data-template="#template_enseigne" href="#">Ajouter une enseigne</a>
        </div>
    </div>
    <div class="form_ligne">
        <?php echo $societeForm['tva_intracom']->renderLabel(); ?>
        <?php echo $societeForm['tva_intracom']->render(); ?>
        <?php echo $societeForm['tva_intracom']->renderError(); ?>
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

            var bloc = $($(this).attr('data-container')).append(bloc_html);

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