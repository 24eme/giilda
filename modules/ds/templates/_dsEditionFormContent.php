<?php
use_helper('Float');
?>
<form id="" action="<?php echo url_for('ds_edition_operateur', $ds); ?>" method="post">
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
    <fieldset id="dsEdition">
        <table id="ds_edition_table" class="table_recap">
        <thead>
            <tr>
                <th>Code</th>
                <th>Produits</th>
                <th>Volume saisie</th>
            </tr>
        </thead>
        <tbody class="ds_edition_tableBody">
            <?php
            foreach ($declarations as $key => $declaration){
                    $d = $declarations->get($key);
                    $prod_vol = $d->produit_libelle;
                    if($d->stock_initial) $prod_vol .= ' ('.getArialFloat($d->stock_initial).' hl)';
                    include_partial('item',array('form' => $form, 'key' => $key, 'declaration' => $d, 'prod_vol' => $prod_vol));
               
            }
    ?>
            <tr id="ds_declaration_lastRow">
            <td class="ds_declaration_code"></td>
            <td class="ds_declaration_appelation">
                <a href="<?php echo url_for('ds_edition_operateur_addProduit', $ds) ?>" id="ds_declaration_new" class="btn_majeur btn_modifier ds_declaration_addTemplate">Ajouter un produit</a>
            </td>
            <td class="ds_declaration_volume_drm">
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

            <a href="<?php echo url_for('ds_etablissement',array('identifiant' => $ds->identifiant)); ?>" class="btn_etape_prec">
                <span>Etape précédente</span>
            </a> 
            <div class="btnValidation">
                <span>&nbsp;</span>
            <button type="submit" id="ds_declaration_valid" class="btn_majeur btn_valider ds_declaration_addTemplate">Suivant</button>

            </div>
        </div>
</fieldset>
</form>

