<?php
use_helper('Float');
use_helper('Date');
use_helper('Prix');
?>
<?php if (!count($mouvements)) : ?>
    <div class="row row-margin">
        <p>Pas de mouvements en attente de facturation</p>
    </div>
<?php else : ?>
    <div class="row row-margin">
        <legend>Mouvements en attente de facturation</legend>
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
                                    SV12Client::getInstance()->getLibelleFromId($mouvement->numero);

                            echo link_to($numeroFormatted, 'facture_redirect_to_doc', array('iddocument' => $mouvement->numero));
                            ?></span>
                        <span class="col-xs-3"><?php echo $mouvement->produit_libelle ?></span>
                        <span class="col-xs-2"><?php echo $mouvement->type_libelle . ' ' . $mouvement->detail_libelle ?></span>
                        <span class="col-xs-2"><?php echoTtc($mouvement->prix_ht); ?>&nbsp;&euro;</span>
                    </li>
                <?php endforeach; ?>
            </div>
        </div>
    </div>   
    <div class="row row-margin">
        <form id="generation_form" action="<?php echo url_for('facture_generer', $societe); ?>" method="post">
            <?php include_partial('facture/datesGeneration', array('form' => $form)) ?>
                <button id="generation_facture" class="btn btn-lg btn-success">Générer une facture pour ces mouvements</button>
         
        </form>
    </div>
<?php endif; ?>