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
             <?php include_partial('fil_ariane'); ?>
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
                    <span id="recherche_avancee"><a href="">> Recherche avancée</a></span>
                </div>
                <br />
                <div class="section_label_maj"> 
                    <label>10 derniers contrats saisis : </label>
                    <div class="legende">
                        <div><span class="type_raisins">type_raisins</span><span class="legende_type_texte">Raisins</span></div>
                        <div><span class="type_mouts">type_mouts</span><span class="legende_type_texte">Mouts</span></div>
                        <div><span class="type_vin_vrac">type_vin_vrac</span><span class="legende_type_texte">Vrac</span></div>
                        <div><span class="type_vin_bouteille">type_vin_bouteille</span><span class="legende_type_texte">Bouteilles</span></div>
                    </div>
                    <div class="legende">
                        <div><span class="statut statut_solde"></span><span class="legende_statut_texte">Soldé</span></div>
                        <div><span class="statut statut_non-solde"></span><span class="legende_statut_texte">Non-soldé</span></div>
                        <div><span class="statut statut_annule"></span><span class="legende_statut_texte">Annulé</span></div>
                    </div>
                    <?php include_partial('table_contrats', array('vracs' => $vracs)); ?>
                </div>
            </div>
        </section>
        <?php include_partial('actions'); ?>
    </div>
</div>