<tr>
    <td><?php echo $prod_vol; ?></td>
    <td><?php
            echo $form['vci_'.$key]->renderError();
            echo $form['vci_'.$key]->render();
    ?></td>
    <td><?php
            echo $form['reserveQualitative_'.$key]->renderError();
            echo $form['reserveQualitative_'.$key]->render();
    ?></td>
    <td><?php
            echo $form['volumeStock_'.$key]->renderError();
            echo $form['volumeStock_'.$key]->render();
    ?></td>
</tr>