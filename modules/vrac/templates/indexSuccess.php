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
<script type="text/javascript">
    $(document).ready(function()
    {
       $('.autocomplete').combobox(); 
    });

</script>
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
             <?php 
             include_partial('fil_ariane',array('fil' => 0));
             ?>
            <div id="contenu_etape">
                <div id="recherche_operateur" class="section_label_maj">
                    <label>Rechercher un opérateur : </label>
                    <form method="get" action="<?php echo url_for('vrac_recherche'); ?>">
                        <select name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>" class="autocomplete">
                            <?php foreach ($etablissements as $id => $name){  ?>
                                <option value="<?php echo preg_replace('/ETABLISSEMENT-/', '',$id); ?>"><?php echo $name; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" id="btn_rechercher">Rechercher</button>
                    </form>
                    <!--<span id="recherche_avancee"><a href="">> Recherche avancée</a></span>-->
                </div>
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