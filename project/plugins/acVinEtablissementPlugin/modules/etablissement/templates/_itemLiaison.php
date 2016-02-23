<div class="form_ligne">
    <div class="form-group form_colonne">
    <?php
    echo $form['type_liaison']->renderError();
    echo $form['type_liaison']->renderLabel(null, array("class" => "col-xs-4 control-label"));
    echo '<div class="col-xs-8">'.$form['type_liaison']->render().'</div>';
    ?>        
    </div>
    <div class="form-group form_colonne">
        <?php
    echo $form['id_etablissement']->renderError();
    echo $form['id_etablissement']->renderLabel(null, array("class" => "col-xs-4 control-label"));
    echo '<div class="col-xs-8">'.$form['id_etablissement']->render().'</div>';
    ?>
    </div>
<a href="#" class="btn_supprimer_ligne_template" data-container="div">Supprimer</a>
</div>
<script type="text/javascript">
        $('.autocomplete').combobox();
</script>
    

