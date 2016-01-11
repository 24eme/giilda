<?php
use_helper('Float');
use_helper('Date');
use_helper('Prix');
?>
<div class="row row-margin">
    <div class="col-xs-8">
        <h2 class="vertical-center" style="margin: 0 0 20px 0;">Mouvements en attente de facturation</h2>
    </div>
    <div class="col-xs-4 text-right">
        <a href="<?php echo url_for('facture_creation', array('identifiant' => $societe->identifiant, 'type-facture' => FactureClient::TYPE_FACTURE_MOUVEMENT_DRM));?>" class="btn btn-sm btn-default">Facturer les mouvements</a>
    </div>
</div>

<?php if (!count($mouvements)) : ?>
    <div class="row row-margin">
        <p class="text-center text-muted">Pas de mouvements en attente de facturation</p>
    </div>
<?php else : ?>
    <div class="row row-margin">
        <div class="col-xs-12">
            <div class="list-group">
                <li class="list-group-item col-xs-12">
                    <span class="col-xs-2">Date</span>
                    <span class="col-xs-3">Document</span>
                    <span class="col-xs-3">Produits</span>
                    <span class="col-xs-2">Type</span>
                    <span class="col-xs-2">Prix TTC</span>
                </li>
                <?php foreach ($mouvements as $mouvement): ?>
                    <li class="list-group-item col-xs-12">
                        <span class="col-xs-2"><?php echo format_date($mouvement->date, 'dd/MM/yyyy'); ?></span>
                        <span class="col-xs-3"><?php
                            $numeroFormatted = (strstr($mouvement->numero, 'DRM') !== false) ? DRMClient::getInstance()->getLibelleFromId($mouvement->numero) :
                                    $mouvement->nom_facture;

                            echo link_to($numeroFormatted, 'facture_redirect_to_doc', array('iddocument' => $mouvement->numero));
                            ?></span>
                        <span class="col-xs-3"><?php echo $mouvement->produit_libelle ?></span>
                        <span class="col-xs-2"><?php echo  $mouvement->type_libelle; ?></span>
                        <span class="col-xs-2"><?php echoTtc($mouvement->prix_ht); ?>&nbsp;&euro;</span>
                    </li>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
   