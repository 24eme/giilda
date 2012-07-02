<?php
use_helper('Vrac');

$urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant));
if(isset($statut)) $urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant,'statut' => $statut));
if(isset($type)) $urlExport = url_for('vrac_exportCsv',array('identifiant' => $identifiant,'type' => $type)); 


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
            <section id="contenu_etape">                
                <div style="margin: 10px;">
                    <h2>Rechercher un opérateur : </h2>
                    <p>
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
                            <input type="submit" value="recherche" class="btn_majeur btn_noir"/>
                        </form>
                    </p>
                </div>                
                <?php 
                    include_partial('rechercheLegende', array('vracs' => $vracs, 'identifiant'=>$identifiant,'actif' => $actif));
                ?>
                <a class="btn_majeur btn_noir" href="<?php echo $urlExport; ?>" >Extraire csv</a>
                <?php
                    if(count($vracs->rows->getRawValue()))
                    {
                        echo '<h2>Contrats saisis : </h2>';
                        include_partial('table_contrats', array('vracs' => $vracs, 'identifiant'=>$identifiant));                
                    }
                    else
                    {
                    echo "<h2>Il n'existe aucun contrat pour cette recherche</h2>";
                    }
                ?>
            </section>
        </section>
        <aside id="colonne">
            <?php include_partial('actions'); ?>
        </aside>
    </div>
</div>


