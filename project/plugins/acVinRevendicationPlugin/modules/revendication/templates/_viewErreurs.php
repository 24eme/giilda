<?php $libellesForErrorsType = RevendicationErrorException::getLibellesForErrorsType(); ?>
<h2>Rapport d'erreurs :</h2>
<div id="rapport_erreurs">
    <p class="nb_erreurs">
        Nombre d'erreurs total : <?php echo $revendication->getNbErreurs(); ?>
    </p>

    <?php foreach ($revendication->erreurs as $type => $erreursType) : ?>
        <div class="type_erreurs">
            <h3><?php echo $libellesForErrorsType[$type]; ?> :</h3>

            <?php foreach ($erreursType as $unmatched_data => $erreurs) : ?>
                <div class="item">

                    <div class="produit">
                        <?php
                        switch ($type) :
                            case RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS: ?>
                        <a href="<?php
                        echo url_for('revendication_add_alias_to_configuration', array('odg' => $revendication->odg,
                            'campagne' => $revendication->campagne,
                            'alias' => $unmatched_data))
                                        ?>" class="btn_majeur btn_voir">Trouver le produit</a>
                                        
                        <?php  break;
                            case RevendicationErrorException::ERREUR_TYPE_DOUBLON: ?>
                                        <?php
                                break;
                               default:
                                   break;
                           endswitch;
                           ?>
                          <p><?php echo $erreurs->getFirst()->libelle_erreur; ?></p>
                    </div>

                    <ul class="num_erreurs">
                        <?php foreach ($erreurs as $pos => $erreur) : ?>
                            <li><a href="#erreur_<?php echo $erreur->num_ligne + 1; ?>"><?php echo $erreur->num_ligne + 1; ?></a></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<h2>Tableau d'erreurs :</h2>
<table id="table_erreurs" class="table_recap">
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
                    <tr id="erreur_<?php echo $erreur->num_ligne +1; ?>">
                        <th><?php echo $erreur->num_ligne + 1; ?></th>
                        <td>
                            <p><?php echo $libellesForErrorsType[$type]; ?></p>
                            <p class="libelle">
                                <?php echo $erreur->libelle_erreur; ?>
                            </p>
                            <p><?php echo str_replace('#', ';', $erreur->ligne); ?></p>
                            <a style="margin-top: 5px;" href="<?php echo url_for('revendication_delete_line', array('odg' => $revendication->odg,'campagne' => $revendication->campagne,'num_ligne' => $erreur->num_ligne,'num_ca' => $erreur->numero_certification)); ?>" class="btn_majeur btn_annuler">Supprimer cette ligne</a>
                        </td>
                    </tr>
                    <?php
                endforeach;
            endforeach;
        endforeach;
        ?>
    </tbody>
</table>