<!-- #principal -->
<section id="principal">
    <ol class="breadcrumb">
        <li><a href="<?php echo url_for('societe'); ?>">Contacts</a></li>
        <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo $societe->raison_sociale; ?></a></li>
        <li class="active">
            <strong>
                <?php echo 'Modification établissement'; ?>
            </strong>
        </li>

    </ol>

    <!-- #contenu_etape -->
    <section id="contacts">

        <div id="<?php echo ($compte->compte_type == CompteClient::TYPE_COMPTE_ETABLISSEMENT) ? 'nouveau_etablissement' : 'nouveau_contact' ?>" class="<?php echo ($compte->compte_type == CompteClient::TYPE_COMPTE_ETABLISSEMENT) ? 'etablissement' : ''; ?> ">
            <h2><?php echo $compte->nom_a_afficher ?></h2>
            <form action="<?php echo url_for('compte_coordonnee_modification', $compte); ?>" method="post" class="form-horizontal">
                <div class="form_btn">
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="<?php echo url_for('compte_visualisation', $compte); ?>" class="btn btn-danger">Annuler</a>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button id="btn_valider" type="submit" class="btn btn-default">Valider</button>
                        </div>
                    </div>
                    <div id="coordonnees_contact" class="form_section ouvert">
                        <?php if ($compte->compte_type == CompteClient::TYPE_COMPTE_ETABLISSEMENT): ?>
                            <h3>Coordonnées de l'etablissement</h3>
                        <?php else: ?>
                            <h3>Coordonnées de l'interlocuteur</h3>
                        <?php endif; ?>
                        <?php include_partial('compte/modificationCoordonnee', array('compteForm' => $compteForm)) ?>
                    </div>  
                    <div class="form_btn">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="<?php echo url_for('compte_visualisation', $compte); ?>" class="btn btn-danger">Annuler</a>
                            </div>
                            <div class="col-xs-6 text-right">
                                <button id="btn_valider" type="submit" class="btn btn-default">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>	
        </div>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Accueil des sociétés</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Accueil de la société</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?> 
