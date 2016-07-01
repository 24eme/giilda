<?php
use_helper('Date');
use_helper('DRM');
use_helper('Orthographe');
use_helper('DRMPdf');
use_helper('Display');


$mvtsEnteesForPdf = $drmLatex->getMvtsEnteesForPdf($detailsNodes);
$mvtsSortiesForPdf = $drmLatex->getMvtsSortiesForPdf($detailsNodes);
?>
<?php foreach ($drm->declaration->getProduitsDetailsByCertificationsForPdf($aggregateAppellation,$detailsNodes) as $typeDrmProduit => $produitsDrmByCertifications): ?>
<?php foreach ($produitsDrmByCertifications as $certification => $produitsDetailsByCertifications) : ?>
  <?php if($aggregateAppellation): ?>
          <?php include_partial('drm_pdf/generateRecapMvtByAppellationTex', array('produitsDetailsByCertifications' => $produitsDetailsByCertifications,'mvtsEnteesForPdf' => $mvtsEnteesForPdf, 'mvtsSortiesForPdf' => $mvtsSortiesForPdf,"libelleDetail" => $libelleDetail)); ?>
  <?php else: ?>
          <?php include_partial('drm_pdf/generateRecapMvtByCepageTex', array('produitsDetailsByCertifications' => $produitsDetailsByCertifications,'mvtsEnteesForPdf' => $mvtsEnteesForPdf, 'mvtsSortiesForPdf' => $mvtsSortiesForPdf,"libelleDetail" => $libelleDetail)); ?>
  <?php endif; ?>
<?php endforeach; ?>
\newpage
<?php endforeach; ?>
