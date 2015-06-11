<tr>
    <td class="export_detail_produit">
        <strong> 
            <?php echo $detail->getLibelle(ESC_RAW); ?>
        </strong>
    </td>
    <td class="export_detail_destination">    
        <?php
        echo $form['identifiant']->renderError();
        echo $form['identifiant']->render();
        ?>
    </td>
    <td class="volume export_detail_volume">    
        <?php
        echo $form['volume']->renderError();
        echo $form['volume']->render();
        ?>
    </td>
    <td class="export_detail_numero_document">    
        <?php
        echo $form['numero_document']->renderError();
        echo $form['numero_document']->render();
        ?>
    </td>   
    <td class="export_detail_remove">    
        <a href="#"  class="btn_majeur btn_annuler drm_details_remove">Supprimer</a>
    </td>
</tr>
