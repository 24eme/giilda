<?php
use_helper('Float');
use_helper('Date');
foreach ($etb->produits as $hashKey => $prod) :
    if ($prod->statut != RevendicationProduits::STATUT_SUPPRIME) :

        foreach ($prod->volumes as $num_row => $volume) :
            ?>
            <tr>
                <td><?php echo format_date($volume->date_insertion,'dd/MM/yyyy'); ?></td>
                <td><?php echo $etb->getKey(); ?></td>
                <td><?php
            echo $etb->declarant_nom;
            if ($volume->bailleur_nom)
                echo ' (en metayage avec : ' . $volume->bailleur_nom . ')';
            ?></td>
                <td><?php echo $prod->produit_libelle; ?></td>
                <td><?php echoFloat($volume->volume); ?></td>
                <td>
                    <a href="<?php
            echo url_for('revendication_edition_row', array('odg' => $etb->getDocument()->odg,
                'campagne' => $etb->getDocument()->campagne,
                'cvi' => $etb->getKey(),
                'row' => $num_row,
                'retour' => $retour));
            ?>">éditer</a>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
endforeach;
?>