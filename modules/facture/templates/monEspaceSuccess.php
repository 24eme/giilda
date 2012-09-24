<?php
use_helper('Float');
?>
<div id="contenu" class="facture">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
          <?php include_component('facture', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
        </section>
        <br />
        
        <?php 
        include_partial('historiqueFactures',array('etablissement' => $etablissement,'factures' => $factures));
        ?>
          <hr />
          <h2>Génération de facture</h2>
          <br />
          <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'etablissement' => $etablissement, 'form'=>$form)) ?>
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>

<script type="text/javascript">
    
    $(document).ready( function()
	{
            $('#generation_facture').bind('click', function()
            {
                $('form#generation_form').submit();
            });
        });
    
</script>

