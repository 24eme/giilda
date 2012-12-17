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
                <th>Produits</th>
                <th>VCI</th>
                <th>Réserve qual.</th>
                <th>Volume saisie</th>
            </tr>
        </thead>
        <tbody class="ds_edition_tableBody">
            <?php
            foreach ($declarations as $key => $declaration){
                
                    $prod_vol = $declaration->produit_libelle;
                    if($declaration->stock_initial) $prod_vol .= ' ('.getArialFloat($declaration->stock_initial).' hl)';
                    include_partial('item',array('form' => $form, 'key' => $key, 'declaration' => $declaration, 'prod_vol' => $prod_vol));
               
            }
    ?>
        </tbody>
        </table>
		
  <input type="submit" class="btn_majeur btn_orange" name="addproduit" value="Ajouter un produit"/>

        <div id="commentaires" class="section_label_maj">
            <label>
                <?php echo $form['commentaires']->renderLabel() ?>
            </label>
            <div class="bloc_form">
                <?php echo $form['commentaires']->renderError() ?>       
                <?php echo $form['commentaires']->render() ?>
            </div>
        </div> 
        
		<div class="btn_etape">
			<a href="<?php echo url_for('ds_etablissement',array('identifiant' => $ds->identifiant)); ?>" class="btn_etape_prec"><span>Etape précédente</span></a> 
			<button type="submit" id="ds_declaration_valid" class="btn_majeur btn_valider ds_declaration_addTemplate">Suivant</button>
		</div>
</fieldset>
</form>

