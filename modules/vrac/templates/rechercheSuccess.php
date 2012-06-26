<?php
use_helper('Vrac');
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
                            <input type="submit" value="recherche"/>
                        </form>
                    </p>
                </div>
                <h2>Contrats saisis : </h2>
                <?php include_partial('table_contrats', array('vracs' => $vracs, 'identifiant'=>$identifiant)); ?>
            </section>
        </section>
        <?php include_partial('actions'); ?>
    </div>
</div>


