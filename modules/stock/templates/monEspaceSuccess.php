<?php
use_helper('Float');
?>
<div id="contenu" class="stock">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('stock') ?>">Page d'accueil</a> &gt; <strong>Stocks : consultation & déclaration</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Consulter les stocks d'un opérateur :</h2>
          <?php include_component('stock', 'chooseEtablissement', array('identifiant' => $operateur->identifiant)); ?>
        </section>
        <br />
        <h2>Détail opérateur</h2>
        <?php 
           include_partial('operateurInformations', array('operateur' => $operateur));
        ?>
        <br />
          <hr />
          <?php include_partial('generationFormulairesOperateur', array('operateur' => $operateur, 'generationOperateurForm' => $generationOperateurForm)); ?>
          <br />
          <hr />
          <h2>Historique des déclarations de stocks</h2>
          <br />
          <?php  include_partial('stocksHistorique', array('stocksHistorique' => $stocksHistorique)) ?>
          <br />
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
            $('#generation_stock').bind('click', function()
            {
                $('form#generation_form').submit();
            });
        });
    
</script>

