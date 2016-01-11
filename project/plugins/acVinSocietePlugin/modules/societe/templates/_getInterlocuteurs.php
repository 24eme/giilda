<?php use_helper('Compte') ?>

<?php
if (!is_null($contacts)):
    ?>
        <h2>Interlocuteurs</h2>

        <div class="contenu">
            <ul class="list-group">
                <?php foreach ($contacts as $id => $contact) :
                    ?>
                    <li class="list-group-item">
                            <?php if (!isset($no_link) || !$no_link): ?>
                                    <h4 class="list-group-item-heading"><span class="<?php echo comptePictoCssClass($contact) ?>"></span> <a title="<?php echo $contact->compte_type ?>" href="<?php echo url_for('compte_visualisation', array('identifiant' => $contact->identifiant)); ?>"><?php echo $contact->nom_a_afficher; ?></a></h4>
                            <?php else: ?>
                                <?php echo $contact->nom_a_afficher; ?>
                            <?php endif; ?>
                        <p class="list-group-item-text">
                            <ul class="list-unstyled">
                                <?php if ($contact->statut && ($contact->statut == SocieteClient::STATUT_SUSPENDU)): ?>
                                    <li><?php echo $contact->statut; ?></li>
                                <?php endif; ?>
                                <?php if ($contact->telephone_perso): ?>
                                    <li><?php echo $contact->telephone_perso; ?></li>
                                <?php endif; ?>
                                <?php if ($contact->telephone_mobile): ?>
                                    <li><?php echo $contact->telephone_mobile; ?></li>
                                <?php endif; ?>
                                <?php if ($contact->telephone_bureau): ?>
                                    <li><?php echo $contact->telephone_bureau; ?></li>
                                    <?php endif; ?>
                                <?php if ($contact->fax): ?>
                                    <li><?php echo $contact->fax; ?></li>
                                <?php endif; ?>
                                <?php if (trim($contact->email)): ?>
                                    <li><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </p>
                    </li>
    <?php endforeach; ?>
            </ul>
        </div>
<?php endif; ?>