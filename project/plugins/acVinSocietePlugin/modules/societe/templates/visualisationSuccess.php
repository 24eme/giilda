<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong> <?php echo $societe->raison_sociale; ?></p>

    <!-- #contenu_etape -->
    <section id="contacts">
        <div id="creation_societe">
        <h2><?php echo $societe->raison_sociale; ?></h2>
        <div class="btn_etape">
            <a href="<?php echo url_for('societe_addContact', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_vert">Nouvel interlocuteur</a>
            &nbsp;
            <?php if($societe->canHaveChais()) : ?>  
            <a href="<?php echo url_for('societe_addEtablissement', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_jaune">Nouvel Etablissement
            </a>
            <?php endif;?>
        </div>
        <?php
        include_partial('visualisationPanel', array('societe' => $societe));?>
            <?php if(count($etablissements)) : ?>
            <h2>Coordonnées de la société </h2>
            <?php endif; ?>
            <?php
        foreach ($etablissements as $etablissementId => $etb) :
            include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre));
        endforeach;
        ?>

    </section>
</section>   

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_addContact',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel interlocuteur</span></a>
        </div>
    </div>
    <?php if($societe->canHaveChais()) : ?>  
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_addEtablissement',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel etablissement</span></a>
        </div>
    </div>
    <?php  endif; ?>
</div>
<?php
end_slot();
?>
