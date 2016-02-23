<div class="panel-body">
    <?php
    echo $etablissementForm->renderHiddenFields();
    echo $etablissementForm->renderGlobalErrors();
    ?>
    <div class="form-group<?php if($etablissementForm['nom']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['nom']->renderError(); ?>
        <?php echo $etablissementForm['nom']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['nom']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['statut']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $etablissementForm['statut']->renderError(); ?>
            <?php echo $etablissementForm['statut']->renderLabel('Statut *', array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $etablissementForm['statut']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['region']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $etablissementForm['region']->renderError(); ?>
            <?php echo $etablissementForm['region']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $etablissementForm['region']->render(); ?></div>
    </div>
<?php /*
             <div id="liaisons_list">
            <?php
            foreach ($etablissementForm['liaisons_operateurs'] as $liaisonForm) {
                include_partial('itemLiaison', array('form' => $liaisonForm));
            }
            ?></div>
            <div class="form-group">
                <a class="btn_ajouter_ligne_template" data-container="#liaisons_list" data-template="#template_liaison" href="#">Ajouter une liaison</a>
            </div>
<?php */
    if (!$etablissement->isCourtier()):
        ?>
        <div class="form-group<?php if($etablissementForm['cvi']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $etablissementForm['cvi']->renderError(); ?>
            <?php echo $etablissementForm['cvi']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $etablissementForm['cvi']->render(); ?></div>
        </div> 
        <?php endif; ?>
     <?php if ($etablissement->isCourtier()): ?>
    <div class="form-group<?php if($etablissementForm['carte_pro']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['carte_pro']->renderError(); ?>
        <?php echo $etablissementForm['carte_pro']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['carte_pro']->render(); ?></div>
    </div>
    <?php endif; ?>
    <div class="form-group<?php if($etablissementForm['no_accises']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['no_accises']->renderError(); ?>
        <?php echo $etablissementForm['no_accises']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['no_accises']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['commentaire']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['commentaire']->renderError(); ?>
        <?php echo $etablissementForm['commentaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['commentaire']->render(); ?></div>
    </div>

</div>
<?php include_partial('templateLiaisonItem', array('form' => $etablissementForm->getFormTemplate()));
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
                
        console.log($($(this).attr('data-template')).html());
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
