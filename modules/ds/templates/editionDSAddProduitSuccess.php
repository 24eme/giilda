<?php
use_helper('Float');
?>
<div id="contenu" class="ds">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('ds') ?>">Page d'accueil</a> &gt; <a href="<?php echo url_for('ds', $ds) ?>">ds_edition_operateur</a> &gt; <strong>Ajouter un produit</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Consulter les stocks d'un opérateur :</h2>
          	<?php include_partial('produitForm', array('ds' => $ds, 'form' => $form)); ?>
		</section>
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
$(document).ready(function () {
		$( "#<?php echo $form['hashref']->renderId() ?>" ).combobox();
});
</script>
