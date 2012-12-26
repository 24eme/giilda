<?php
if (!is_null($contacts)):
    ?>
    <div class="bloc_col" id="infos_contact">
        <h2>Interlocuteurs</h2>

        <div class="contenu">
            <ul>
                <?php foreach ($contacts as $id => $contact) : ?>
                    <li id="infos_contact_vendeur">
                        <a href="<?php echo url_for('compte_modification', array('identifiant' => $contact->identifiant)); ?>">Coordonnées de <?php echo $contact->nom_a_afficher; ?></a>
                        <ul>
                            <li class="nom"><?php echo $contact->nom_a_afficher; ?></li>
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