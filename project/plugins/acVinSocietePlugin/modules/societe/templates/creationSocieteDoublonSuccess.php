<!-- #principal -->
<?php use_helper('Compte') ?>
<section class="row">
    <ol class="breadcrumb">
        <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
        <li><a href="<?php echo url_for('societe_creation'); ?>"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Création d'une société </a></li>
        <li class="active"><a href="">Sociétés existantes</a></li>
    </ol>

    <h2>Sociétés existantes</h2>
    <div class="col-xs-12 ">
        <div class="well">
            <span>
                Les sociétés suivantes possède un raison sociale proche de <strong>"<?php echo $raison_sociale; ?>"</strong>.
            </span>
        </div>
    </div>
    <br>

    <div style="" class="col-xs-12">
        <?php foreach ($societesDoublons as $societeDoublee) : ?>
            <div class="list-group">
                <div class="list-group-item">
                    <h2 style="margin-top: 5px; margin-bottom: 5px;"><span class="glyphicon glyphicon-calendar"></span>
                        <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT])); ?>">
                            <?php echo $societeDoublee->key[SocieteAllView::KEY_RAISON_SOCIALE]; ?>
                        </a>
                        <small class="text-muted">(n° de societe : <?php echo $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT]; ?>)</small>
                </div>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-xs-12  text-center">
                            <address class="">
                                <?php echo $societeDoublee->key[SocieteAllView::KEY_COMMUNE]; ?>&nbsp;<?php echo $societeDoublee->key[SocieteAllView::KEY_CODE_POSTAL]; ?>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <ul class="list-inline">
                        <li><attr>N° SIRET :</attr> <?php echo formatSIRET($societeDoublee->key[SocieteAllView::KEY_SIRET]); ?></li>
                    </ul>
                </div>
            </div>
            <br/>
        <?php endforeach; ?>
    </div>

    <div class="form_btn">
        <div class="col-xs-6">
            <a class="btn btn-default" href="<?php echo url_for('societe_creation'); ?>">Annuler</a>
        </div>
        <div class="col-xs-6  text-right">
            <a class="btn btn-success" href="<?php echo url_for('societe_nouvelle', array('raison_sociale' => $raison_sociale)); ?>">Créer</a>
        </div>
    </div>
</section>
