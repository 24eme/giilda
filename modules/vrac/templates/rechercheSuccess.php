<?php
use_helper('Vrac');

$fil_arianeArray = array('fil' => ((isset($type) || isset($statut))+1), 'identifiant' => $identifiant);

$urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant));
if(isset($statut)){ 
    $urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant,'statut' => $statut));
    $fil_arianeArray['statut'] = $statut;
}
if(isset($type)){
    $urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant,'type' => $type));
    $fil_arianeArray['type'] = $type;
}
if(!isset($campagne)) $campagne = dateCampagneViticolePresent();
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
             <?php include_partial('fil_ariane',$fil_arianeArray); ?>
            <section id="contenu_etape">                
                <div id="recherche_operateur" class="section_label_maj">
                    <label>Rechercher un opérateur : </label>
                    <form method="get" action="<?php echo url_for('vrac_recherche'); ?>">
                            <select name="identifiant" value="<?php echo (isset($identifiant)) ? $identifiant : '' ; ?>" class="autocomplete">
                                <?php foreach ($etablissements as $id => $name)
                                {
                                    $localEtablissement = preg_replace('/ETABLISSEMENT-/', '',$id);
                                ?>
                                    <option value="<?php echo $localEtablissement; ?>"<?php echo ($identifiant==$localEtablissement)? 'selected="selected"' : '' ; ?>><?php echo $name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <button type="submit" id="btn_rechercher">Rechercher</button>
                            <!--<span id="recherche_avancee"><a href="">> Recherche avancée</a></span>-->
                        </form>
                </div>         
                <a id="btn_export_csv" href="<?php echo $urlExport; ?>" >Ouvrir en tableur</a>
                <?php 
                    include_partial('rechercheLegende', array('rechercheMode' => true, 'vracs' => $vracs, 'identifiant'=>$identifiant,'actif' => $actif));
                ?>
                <div class="section_label_maj">  
                <?php
                    if(count($vracs->rows->getRawValue()))
                    {
                        echo '<label>Contrats saisis : </label>';
                        include_partial('table_contrats', array('vracs' => $vracs, 'identifiant'=>$identifiant));                
                    }
                    else
                    {
                    echo "<label>Il n'existe aucun contrat pour cette recherche</label>";
                    }
                ?>
                </div>
            </section>
        </section>
        <aside id="colonne">
            <?php include_partial('actions'); ?>
            <?php include_partial('contrat_campagne',array('vracs' => $vracs, 'visualisation' => false,'campagne' => $campagne)); ?>
        </aside>
    </div>
</div>


