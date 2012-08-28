<div id="contenu" class="facture">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $etablissement->nom ?></strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
          <?php include_component('facture', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant)); ?>
        </section>
        <br />
        <h2>Historique des factures</h2>
        <fieldset>
             <table class="table_recap">
             <thead>
             <tr>
               <th>Date</th>
               <th>DRM liées</th>
               <th>Prix TTC</th>
             </tr>
             </thead>
             <tbody>
             <?php foreach ($factures->getRawValue() as $facture) : ?>
             <tr>
                 <td><?php echo link_to($facture->value[0],array('sf_route' => 'facture_pdf', 'identifiant' => str_replace('ETABLISSEMENT-', '', $facture->key[0]), 'factureid' => str_replace('FACTURE-'.$etablissement->identifiant.'-', '', $facture->key[1]))); ?></td>
                 <td><?php foreach ($facture->value[1] as $drmid => $drmlibelle){ echo link_to($drmlibelle, 'drm_redirect_to_visualisation', array('identifiant_drm'=>$drmid))."<br/>"; }; ?></td>
                 <td><?php echo $facture->value[2]; ?>&nbsp;€</td>
             </tr>
             <?php endforeach; ?>
             </tbody>
             </table>
          </fieldset>
          <hr />
          <h2>Génération de facture</h2>
          <br />
          <?php include_partial('facture/mouvements', array('mouvements' => $mouvements)) ?>
          <br />
          <a href="<?php echo url_for('facture_generer',$etablissement); ?>" class="btn_majeur btn_vert">Générer</a>
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
