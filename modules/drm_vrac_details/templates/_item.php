<tr>
        <td class="export_detail_produit">
            <strong><?php echo $detail->getLibelle(ESC_RAW); ?></strong>
        </td>
        <td class="export_detail_numero_contrat">    
            <?php
            echo $form['numero_contrat']->renderError();
            echo $form['numero_contrat']->render();
            ?>
        </td>
        <td class="export_detail_volume">    
            <?php
            echo $form['volume']->renderError();
            echo $form['volume']->render();
            ?>
        </td>
        <td class="export_detail_date_enlevement champ_datepicker">    
            <?php
            echo $form['date_enlevement']->renderError();
            echo $form['date_enlevement']->render();
            ?>
        </td>   
        <td class="export_detail_remove">    
            <a href="#"  class="btn_majeur btn_rouge drm_details_remove">Supprimer</a>
        </td>  
</tr>