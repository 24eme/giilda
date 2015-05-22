<?php
use_helper('Date');
use_helper('Relance');
?>
<h2>Historique des Relances</h2>
<?php
if(count($relances->getRawValue())==0) :
?>
<p>
    Il n'existe aucune relance générée pour cet établissement
</p>
<?php else : ?>
<fieldset>
    <table class="table_recap">
        <thead>
            <tr>
                <th>Date</th>
                <th>Référence</th>
                <th>Type de relance</th>
                <th>Alertes relancées</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($relances->getRawValue() as $relance) :
                ?>
                <tr>
                    <td><?php 
                        $d = format_date($relance->key[RelanceEtablissementView::KEY_DATE_CREATION],'dd/MM/yyyy');
                        echo link_to($d, array('sf_route' => 'relance_pdf', 'idrelance' => $relance->id)); ?>
                    </td>
                    <td><?php echo $relance->key[RelanceEtablissementView::KEY_REFERENCE]; ?></td>
                    <td><?php echoTypeRelance($relance->key[RelanceEtablissementView::KEY_TYPE_RELANCE]); ?></td>
                    <td><?php foreach ($relance->value[RelanceEtablissementView::VALUE_ORIGINES] as $id => $libelle) {
                        $alerte =  AlerteClient::getInstance()->find($id);
                        echo link_to($alerte->getLibelle(), 'alerte_modification', $alerte) . "<br/>";
            } ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</fieldset>
<?php endif; ?>