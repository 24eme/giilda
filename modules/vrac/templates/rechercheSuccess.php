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
<div id="contenu">
    <div id="rub_contrats">
        <section id="principal">
             <?php include_partial('fil_ariane',$fil_arianeArray); ?>
            <section id="contenu_etape">                
                <?php include_component('vrac', 'formEtablissementChoice', array('identifiant' => $identifiant)) ?>
                <a id="btn_export_csv" href="<?php echo $urlExport; ?>" >Ouvrir en tableur</a>
                <?php 
                    include_partial('rechercheLegende', array('rechercheMode' => true,
                                                              'vracs' => $vracs,
                                                              'identifiant'=>$identifiant,
                                                              'actifs' => $actifs,
                                                              'multiCritereType' => $multiCritereType,
                                                              'multiCritereStatut'=> $multiCritereStatut,
                                                              'type' => $type,
                                                              'statut' => $statut));
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


