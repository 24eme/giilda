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
        echo $form['volume']->render();
        echo $form['volume']->renderError();
        ?>
    </td>
    <?php if ($isTeledeclarationMode) : ?>
        <td class="export_detail_type_document">    
            <?php
            echo $form['type_document']->renderError();
            echo $form['type_document']->render();
            ?>
        </td>   
        <td class="export_detail_numero_document">    
            <?php
            echo $form['numero_document']->renderError();
            echo $form['numero_document']->render();
            ?>
        </td>   
    <?php endif; ?>
    <td class="export_detail_remove">    
        <a href="#"  class="btn_majeur btn_annuler drm_details_remove">Supprimer</a>
    </td>
</tr>
