<?php $libellesForErrorsType = RevendicationErrorException::getLibellesForErrorsType(); ?>
<h2>Rapport d'erreurs :</h2>
<div class="generation_facture_options">
    <span>
        Nombre d'erreurs total : <label><?php echo $revendication->getNbErreurs(); ?></label>
    </span>
    <?php foreach ($revendication->erreurs as $type => $erreursType) : ?>
        <div>
            <span><?php echo $libellesForErrorsType[$type]; ?></span>
            <?php foreach ($erreursType as $unmatched_data => $erreurs) : ?>
                <strong><?php echo $erreurs[0]->libelle_erreur; ?></strong>       
                <?php if ($type == RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS): ?>
                    <a href="<?php
            echo url_for('revendication_add_alias_to_configuration', array('odg' => $revendication->odg,
                'campagne' => $revendication->campagne,
                'alias' => $unmatched_data))
                    ?>" class="btnModification">Trouver le produit</a>
        <?php endif; ?>
                <br>
                <div style="display: inline-block;">
                    &nbsp;
                    <?php foreach ($erreurs as $pos => $erreur) : ?>
                        <a href="#<?php echo $erreur->num_ligne; ?>"><?php echo $erreur->num_ligne; ?></a>&nbsp;
                <?php endforeach; ?>
                </div>
                <br>
        <?php endforeach; ?>
        </div>
        <br>
        <?php
    endforeach;
    ?>
</div>
<h2>Tableau d'erreurs :</h2>
<fieldset>
    <table class="table_recap">
        <thead>
            <tr>
                <th>N° de ligne</th>
                <th>Libellé de l'erreur</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($revendication->erreurs as $type => $erreursType) :
                foreach ($erreursType as $unmatched_data => $erreurs):
                    foreach ($erreurs as $pos => $erreur):
                        ?>
                        <tr id="<?php echo $erreur->num_ligne; ?>">
                            <td rowspan="2"><?php echo $erreur->num_ligne; ?></td>
                            <td><?php echo $erreur->libelle_erreur; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo str_replace('#', ';', $erreur->ligne); ?></td>
                        </tr>
                        <?php
                    endforeach;
                endforeach;
            endforeach;
            ?>
        </tbody>
    </table>
</fieldset>