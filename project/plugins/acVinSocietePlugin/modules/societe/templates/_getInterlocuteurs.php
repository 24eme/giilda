<?php
if (!is_null($contacts)):
    ?>
    <div class="bloc_col" id="infos_contact">
        <h2>Interlocuteurs</h2>

        <div class="contenu">
            <ul>
                <?php foreach ($contacts as $id => $contact) : 
                    ?>
                    <li id="infos_contact_vendeur">
                        <!--<a href="<?php echo url_for('compte_visualisation', array('identifiant' => $contact->identifiant)); ?>">Coordonnées de <?php echo $contact->nom_a_afficher; ?></a>-->
                        <ul>
                            <?php if ($contact->statut && ($contact->statut == SocieteClient::STATUT_SUSPENDU)): ?>
                                <li style="color: red"><?php echo $contact->statut; ?></li>
                            <?php endif; ?>
                            <li class="titre <?php if ($contact->compte_type == CompteClient::TYPE_COMPTE_SOCIETE) { echo 'societe'; } else if ($contact->compte_type == CompteClient::TYPE_COMPTE_ETABLISSEMENT) {echo 'etablissement'; } else {echo 'nom';} ?>">
                                <a title="<?php echo $contact->compte_type ?>" href="<?php echo url_for('compte_visualisation', array('identifiant' => $contact->identifiant)); ?>"><?php echo $contact->nom_a_afficher; ?></a>
                            </li>
                            <?php if ($contact->telephone_perso): ?>
                                <li class="tel_perso"><?php echo $contact->telephone_perso; ?></li>
                            <?php endif; ?>
                            <?php if ($contact->telephone_mobile): ?>
                                <li class="tel_mobile"><?php echo $contact->telephone_mobile; ?></li>
                            <?php endif; ?>
                            <?php if ($contact->telephone_bureau): ?>
                                <li class="tel"><?php echo $contact->telephone_bureau; ?></li>
                            <?php endif; ?>
                            <?php if ($contact->fax): ?>
                                <li class="fax"><?php echo $contact->fax; ?></li>
                            <?php endif; ?>
                            <?php if (trim($contact->email)): ?>
                                <li class="email"><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></li>
                            <?php endif; ?>    
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
endif;
?>