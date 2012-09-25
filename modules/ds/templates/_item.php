<tr>
    <td><?php echo '0000'; ?></td>
    <td><?php echo $declaration->produit_libelle; ?></td>
    <td><?php echo $declaration->stock_initial;?> </td>
    <td><?php
            echo $form[$key]->renderError();
            echo $form[$key]->render();
    ?></td>
</tr>