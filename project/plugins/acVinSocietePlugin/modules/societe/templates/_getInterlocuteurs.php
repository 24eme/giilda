<div class="bloc_col" id="infos_contact">
    <h2>Interlocuteurs</h2>

    <div class="contenu">
        <ul>
            <?php foreach ($contacts as $id => $contact) : ?>
            <li id="infos_contact_vendeur">
                <a href="<?php echo url_for('compte_modification',array('identifiant' => $contact->identifiant)); ?>">Coordonnées de <?php echo $contact->nom_a_afficher; ?></a>
                    <ul>
                        <li class="nom"><?php echo $contact->nom_a_afficher; ?></li>
                        <li class="tel"><?php echo $contact->telephone_bureau; ?></li>
                        <li class="fax"><?php echo $contact->fax; ?></li>
                        <li class="email"><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></li>
                    </ul>
            </li>
                <?php endforeach; ?>
        </ul>
    </div>
</div>