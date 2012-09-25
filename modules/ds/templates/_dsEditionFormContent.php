<form id="" action="<?php echo url_for('ds_edition_operateur',array('identifiant' => $ds->identifiant,'campagne' => $ds->campagne)); ?>" method="post">
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
    <fieldset id="dsEdition">
        <table id="ds_edition_table" class="table_recap">
        <thead>
            <tr>
                <th>Code</th>
                <th>Appelation</th>
                <th>Volume DRM</th>
                <th>Volume Stock</th>
            </tr>
        </thead>
        <tbody class="ds_edition_tableBody">
            <?php
            foreach ($declarations as $key => $declaration){
                    include_partial('item',array('form' => $form, 'key' => $key, 'declaration' => $declarations->get($key)));
               
            }
    ?>
            <tr id="ds_declaration_lastRow">
            <td class="ds_declaration_code"></td>
            <td class="ds_declaration_appelation">
                <a href="<?php echo url_for('ds_edition_operateur_addProduit',array('identifiant' => $ds->identifiant,'campagne' => $ds->campagne)) ?>" id="ds_declaration_new" class="btn_majeur btn_modifier ds_declaration_addTemplate">Ajouter un produit</a>
            </td>
            <td class="ds_declaration_volume_drm">
            </td>
            <td class="ds_declaration_appelation">
                
            </td>               
                  
        </tr>
        </tbody>
        </table> 
        <div id="commentaires" class="section_label_maj">
            <label>
                <?php echo $form['commentaires']->renderLabel() ?>
            </label>
            <div class="bloc_form">
                <?php echo $form['commentaires']->renderError() ?>       
                <?php echo $form['commentaires']->render() ?>
            </div>
        </div> 
        
         <div id="ligne_btn">

            <a href="<?php echo url_for('ds_edition_operateur',array('identifiant' => $ds->identifiant,'campagne' => $ds->campagne)); ?>" class="btn_etape_prec">
                <span>Etape précédente</span>
            </a> 
            <div class="btnValidation">
                <span>&nbsp;</span>
            <button type="submit" id="ds_declaration_valid" class="btn_majeur btn_valider ds_declaration_addTemplate">Suivant</button>

            </div>
        </div>
</fieldset>
</form>

