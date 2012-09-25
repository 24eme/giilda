<?php $key = str_replace('/', '-', $form['produit_hash']->getValue()); ?> 
<tr>
    <td><?php echo '0000'; ?></td>
    <td><?php echo $declarations->$key->produit_libelle; ?></td>
    <td><?php echo $declarations->$key->stock_initial;?> </td>
    <td><?php
                echo $form['stock_revendique']->renderError();
                echo $form['stock_revendique']->render();
    ?></td>
</tr>