<?php
use_helper('Vrac');

$etablissements = array('' => '');
$datas = EtablissementClient::getInstance()->findAll()->rows;
foreach($datas as $data) 
{
        $labels = array($data->key[4], $data->key[3], $data->key[1]);
        $etablissements[$data->id] = implode(', ', array_filter($labels));
}
?>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
             <?php 
             include_partial('fil_ariane',array('fil' => 0));
             ?>
            <div id="contenu_etape">
                <?php include_component('vrac', 'formEtablissementChoice') ?>
                <br />
                <div class="section_label_maj"> 
                    <label>10 derniers contrats saisis : </label>                   
                    <?php include_partial('table_contrats', array('vracs' => $vracs)); ?>
                </div>
            </div>
        </section>
        <aside id="colonne">
        <?php 
        include_partial('actions');
        ?>
        <div id="action" class="bloc_col">
        <h2>Légende</h2>
        <?php
        include_partial('rechercheLegende');
        ?>
        </div>
        <?php
        include_partial('contrat_aide');
        ?>
        </aside>
    </div>
</div>