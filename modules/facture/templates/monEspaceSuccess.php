<section id="contenu">
<section id="etablissement">
<h2>Etablissements</h2>
<?php include_component('facture', 'chooseEtablissement', array('identifiant' => $etablissement->identifiant));  ?>
</section>
<section id="historique">
<h2>Historique des factures</h2>
   <table class="tableau_recap">
   <thead>
   <tr>
   <th style="font-weight: bold; border: none;">Date</th>
   <th style="font-weight: bold; border: none;">DRM liées</th>
   <th style="font-weight: bold; border: none;">Prix TTL</th>
   </tr>
   </thead>
   <tbody>
   <?php foreach ($factures->getRawValue() as $facture) : ?>
   <tr >
   <td><?php echo $facture->value[0]; ?></td>
   <td><?php foreach ($facture->value[1] as $drmid => $drmlibelle){ echo link_to($drmlibelle, 'drm_redirect_to_visualisation', array('identifiant_drm'=>$drmid))."<br/>"; }; ?></td>
   <td><?php echo $facture->value[2]; ?>&nbsp;€</td>
   </tr>
   <?php endforeach; ?>
   </tbody>
   </table>
</section>
</section>