<?php use_helper('Compte'); ?>
<?php $compte = $etablissement->getMasterCompte(); ?>

<h4>
<?php if ($compte->exist('en_alerte') && $compte->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
    <span class="glyphicon glyphicon-home"></span> <?php  echo $etablissement->getNom() ?>
    <span class="text-muted"> -
    <?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille); ?> -
    <?php echo $etablissement->getIdentifiant(); ?>
    <?php  if($etablissement->getCvi()){ echo ' - CVI : '.$etablissement->getCvi(); } elseif($etablissement->ppm){ echo ' - PPM : '.$etablissement->ppm; } ?>
    <?php  if($etablissement->getSiret()){ echo ' - SIRET : '.formatSIRET($etablissement->getSiret()); } ?>
    </span>
</h4>
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
                Adresse&nbsp;:
            </div>
            <div style="margin-bottom: 5px" class="col-xs-9">
                <address style="margin-bottom: 0;">
                    <?php echo $compte->getAdresse(); ?><?php echo ($compte->getAdresseComplementaire())? " ".$compte->getAdresseComplementaire() : ''; ?>
                    <span><?php echo $compte->getCodePostal(); ?></span> <?php echo $compte->getCommune(); ?> <small class="text-muted">(<?php echo $compte->getPays(); ?>)</small>
                </address>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
                Contact&nbsp;:
            </div>
            <div style="margin-bottom: 5px" class="col-xs-9">
                <?php echo ($compte->getEmail())? "<a href='mailto:".$compte->getEmail()."'>".$compte->getEmail()."</a> / " : ''; ?>
                <?php echo ($compte->getTelephoneBureau())? "<a href='callto:".$compte->getTelephoneBureau()."'>".$compte->getTelephoneBureau()."</a> / " : ''; ?>
                <?php echo ($compte->getTelephoneMobile())? "<a href='callto:".$compte->getTelephoneMobile()."'>".$compte->getTelephoneMobile()."</a>" : ''; ?>
            </div>
        </div>
    </div>
</div>
