<section id="principal">
    <h2>Annuaire de vos contacts</h2>

    <div class="fond">
        <div class="annuaire clearfix">

            <div class="bloc_annuaire">

                <div style="text-align: right; margin: 10px 0;">
                    <a href="<?php echo url_for('annuaire_selectionner', array('type' => 'recoltants', 'identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un viticulteur</a>
                </div>

                <table class="table_recap table_annuaire">		
                    <thead>
                        <tr>
                            <th colspan="2">Viticulteurs (<?php echo count($annuaire->recoltants) ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($annuaire->recoltants) > 0): ?>
                            <?php foreach ($annuaire->recoltants as $key => $item): ?>
                                <tr<?= ($item->isActif) ? '' : " class='suspendu'"?>>
                                <td>
                                    <?= $item->name ?>
                                    <?php if (! $item->isActif) : ?>
                                        <span class="red">SUSPENDU</span>
                                    <?php endif;?>
                                    <br/>
                                    <span><?= $key; ?> &middot; CVI: <?= $item->cvi ?> &middot; Accise: <?= $item->accises ?></span>
                                </td>
                                    <td><a href="<?php echo url_for('annuaire_supprimer', array('type' => 'recoltants', 'id' => $key, 'identifiant' => $etablissement->identifiant)) ?>" onclick="return confirm('Confirmez-vous la suppression du viticulteur ?')" class="btn_supprimer">X</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td><span>Aucun viticulteur</span></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($isCourtierResponsable): ?>
                <div class="bloc_annuaire">

                    <div style="text-align: right; margin: 10px 0;">
                        <a href="<?php echo url_for('annuaire_selectionner', array('type' => 'negociants', 'identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un négociant</a>
                    </div>

                    <table class="table_recap">			
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: left; padding-left: 5px;">Négociants (<?php echo count($annuaire->negociants) ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($annuaire->negociants) > 0): ?>
                                <?php foreach ($annuaire->negociants as $key => $item): ?>
                                    <tr>
                                        <?php if ($item->isActif): ?>
                                        <td style="text-align: left; padding-left: 5px;"><?php echo $item->name ?> <?php echo $item->isActif ?> <span style="color: #808080; font-size: 11px;">(<?php echo $key; ?>)</span></td>
                                        <?php else: ?>
                                        <td style="text-align: left; padding-left: 5px;"><span style="text-decoration: line-through;"><?php echo $item->name ?></span> <span style="color: #808080; font-size: 11px; text-decoration: line-through;">(<?php echo $key; ?>)</span> <span style="color: red; font-size: 11px;">SUSPENDU</span></td>
                                    <?php endif; ?>
                                        <td><a href="<?php echo url_for('annuaire_supprimer', array('type' => 'negociants', 'id' => $key, 'identifiant' => $etablissement->identifiant)) ?>" onclick="return confirm('Confirmez-vous la suppression du négociant ?')" class="btn_supprimer">X</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td style="text-align: left; padding-left: 5px;"><span style="font-style: italic; font-size: 11px;">Aucun négociant</span></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>                
                <div class="bloc_annuaire">

                    <div style="text-align: right; margin: 10px 0;">
                        <a href="<?php echo url_for('annuaire_commercial_ajouter', array('identifiant' => $etablissement->identifiant)) ?>" class="btn_vert btn_majeur">Ajouter un commercial</a>
                    </div>

                    <table class="table_recap">			
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align: left; padding-left: 5px;">Commerciaux (<?php echo count($annuaire->commerciaux) ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($annuaire->commerciaux) > 0): ?>
                                <?php foreach ($annuaire->commerciaux as $key => $item): ?>
                                    <tr>
                                        <td style="text-align: left; padding-left: 5px;"><?php echo $item ?> <span style="color: #808080; font-size: 11px;">(<?php echo $key; ?>)</span></td>
                                        <td><a href="<?php echo url_for('annuaire_supprimer', array('type' => 'commerciaux', 'id' => $key, 'identifiant' => $etablissement->identifiant)) ?>" onclick="return confirm('Confirmez-vous la suppression du commercial ?')" class="btn_supprimer">X</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td style="text-align: left; padding-left: 5px;"><span style="font-style: italic; font-size: 11px;">Aucun commercial</span></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissement->identifiant)) ?>">Retourner à l'espace contrats</a>
    <?php include_partial('vrac/popup_notices'); ?> 
</section>

<?php
include_partial('vrac/colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
?>

