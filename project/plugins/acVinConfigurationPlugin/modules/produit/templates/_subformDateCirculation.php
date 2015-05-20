<div class="ligne_form" data-key="<?php echo $form->getName() ?>">
    <table>
        <tbody>
            <tr>
                <td style="padding-left: 10px;">
                    <span class="error">
                        <?php echo $form['campagne']->renderError() ?>
                    </span>
                    <span class="champ_datepicker">
                        <?php echo $form['campagne']->renderLabel() ?>
                    </span>
                    <br /><?php echo $form['campagne']->render() ?></td>
                <td>
                    <ul>
                        <li class="champ_datepicker">
                            <span class="error"><?php echo $form['date_debut']->renderError() ?></span>
                            <?php echo $form['date_debut']->renderLabel() ?><?php echo $form['date_debut']->render() ?>
                        </li>
                        <li class="champ_datepicker">
                            <span class="error"><?php echo $form['date_fin']->renderError() ?></span><?php echo $form['date_fin']->renderLabel() ?>
                            <?php echo $form['date_fin']->render() ?>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>

