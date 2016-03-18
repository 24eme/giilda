<!-- #principal -->
<section id="principal">
    <ol class="breadcrumb">
        <li><a href="<?php echo url_for('societe'); ?>">Contacts</a></li>
        <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo $societe->raison_sociale; ?></a></li>
        <?php if (!$etablissement->isNew()) : ?>
            <li>
                <a href="<?php echo url_for('etablissement_visualisation', array('identifiant' => $etablissement->identifiant)); ?>"><span class="glyphicon glyphicon-home"></span>&nbsp;<?php echo $etablissement->nom; ?>
                </a>
            </li>
        <?php endif; ?>
            <li class="active">
                <strong>
                    <?php echo ($etablissement->isNew()) ? 'Nouvel établissement' : 'Modification établissement'; ?>
                </strong>
            </li>
    </ol>

    <!-- #contenu_etape -->
    <section id="contacts">
        <div class="col-md-8 col-md-offset-2">
            <h2><?php echo ($etablissement->isNew()) ? 'Nouvel établissement' : $etablissement->nom; ?></h2>
            <form class="form-horizontal" action="<?php echo ($etablissement->isNew()) ? url_for('etablissement_ajout', array('identifiant' => $societe->identifiant, 'famille' => $famille)) : url_for('etablissement_modification', array('identifiant' => $etablissement->identifiant)); ?>" method="post">
                <div id="detail_societe" class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Détail de l'établissement</h3></div>
                    <?php include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm, 'etablissement' => $etablissement)); ?>
                </div>
<!--                <div id="coordonnees_etablissement" class="panel panel-default etablissement form_section ouvert">
                    <div class="panel-heading"><h3 class="panel-title">Coordonnées de l'établissement</h3></div>  -->
                    <?php include_partial('compte/modificationCoordonnee', array('compteForm' => $etablissementModificationForm, 'compteSociete' => $etablissement->getSociete()->getMasterCompte())) ?>

                    <?php //include_partial('compte/modificationCoordonneeSameSocieteForm', array('form' => $etablissementModificationForm)); ?>
                <!--</div>-->
                <div class="form_btn">
                    <div class="col-xs-6">
                        <?php if ($etablissement->isNew()): ?>
                            <a href="<?php echo url_for('societe_visualisation', $societe); ?>" type="submit" class="btn btn-default">Annuler</a>
                        <?php else: ?>
                            <a href="<?php echo url_for('etablissement_visualisation', $etablissement); ?>" type="submit" class="btn btn-default">Annuler</a>
                        <?php endif; ?>
                    </div><div class="col-xs-6 text-right">
                        <button id="btn_valider" type="submit" class="btn btn-success">
                            <?php echo ($etablissement->isSameCompteThanSociete()) ? 'Valider et saisir les coordonnées' : 'Valider' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>
