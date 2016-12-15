<?php include_partial('sv12/breadcrumb', array('sv12' => $sv12)); ?>

<section id="principal" class="sv12">
    <div class="row" style="opacity: 0.7">
        <div class="col-xs-12">
             <?php include_component('sv12', 'formEtablissementChoice', array('identifiant' => $sv12->etablissement->_id, 'autofocus' => true)) ?>
        </div>
    </div>

    <h2>
        SV12 de <?php echo $sv12->campagne; ?>
        <?php if ($sv12->isModifiable()): ?>
        <div class="pull-right"><a class="btn btn-warning" href="<?php echo url_for('sv12_modificative', $sv12) ?>">Modifier la SV12</a></div>
        <?php endif; ?>
    </h2>

        <?php if(!$sv12->isMaster()): ?>
            <div class="alert alert-warning">
                Ce n'est pas la <a href="<?php echo url_for('sv12_visualisation', $sv12->getMaster()) ?>">dernière version</a> de la SV12, le tableau récapitulatif n'est donc pas à jour.
            </div>
        <?php endif; ?>

        <?php if(count($contrats_non_saisis) > 0): ?>
            <h3>Contrats sans volume saisie</h3>
            <?php include_partial('contrats', array('contrats' => $contrats_non_saisis)); ?>
        <?php endif; ?>

        <h3>Récapitulatif</h3>
        <?php include_partial('totaux', array('sv12' => $sv12)); ?>

        <h3>Mouvements</h3>
        <?php include_partial('mouvements',array('mouvements' => $mouvements, 'hamza_style' => true)); ?>

        <div class="row">
            <div class="col-xs-4">
                <a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Retour à l'espace de l'opérateur</a>
            </div>
        </div>
</section>
