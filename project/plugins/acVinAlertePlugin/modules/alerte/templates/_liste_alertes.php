<form action="<?php echo url_for('alerte_modification_statuts'); ?>" method="post" >
<div class="generation_facture_options">
        <?php
        echo $modificationStatutForm->renderHiddenFields();
        echo $modificationStatutForm->renderGlobalErrors();
        ?>
<fieldset>
        <section>
            <div>
                <?php echo $modificationStatutForm['statut_all_alertes']->renderError(); ?>
                <?php echo $modificationStatutForm['statut_all_alertes']->renderLabel() ?>
                <?php echo $modificationStatutForm['statut_all_alertes']->render() ?> 
            </div>
        </section>
</fieldset>    
<fieldset>
        <section>
            <div>
                <?php echo $modificationStatutForm['commentaire_all_alertes']->renderError(); ?>
                <?php echo $modificationStatutForm['commentaire_all_alertes']->renderLabel() ?>
                <?php echo $modificationStatutForm['commentaire_all_alertes']->render() ?> 
            </div>
        </section>
</fieldset>    
    <div>
        <input type="submit" value="Valider" class="btn_majeur btn_modifier">
    </div>
</div>
<br/>
<?php
include_partial('history_alertes',array('alertesHistorique' => $alertesHistorique, 'modificationStatutForm' => $modificationStatutForm));
?>
</form>
