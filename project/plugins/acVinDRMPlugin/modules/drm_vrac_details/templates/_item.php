<tr>
    <td class="vrac_detail_produit">
        <strong><?php echo $detail->getLibelle(ESC_RAW); ?></strong>
    </td>
    <td class="vrac_detail_numero_contrat">    
        <?php
        echo $form['identifiant']->renderError();
        echo $form['identifiant']->render();
        ?>
    </td>
    <td class="volume vrac_detail_volume">    
        <?php
        echo $form['volume']->render();
        echo $form['volume']->renderError();
        ?>
    </td>
    <td class="export_detail_numero_document">    
        <?php
        echo $form['numero_document']->renderError();
        echo $form['numero_document']->render();
        ?>
    </td>    
    <td class="vrac_detail_remove">    
        <a href="#"  class="btn_majeur btn_annuler drm_details_remove">&nbsp;</a>
    </td>  
</tr>