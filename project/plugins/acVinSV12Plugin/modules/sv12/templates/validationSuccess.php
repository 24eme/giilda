<?php include_partial('sv12/breadcrumb', array('sv12' => $sv12)); ?>

<section id="principal" class="sv12">
    <?php include_partial('sv12/etapes', array('sv12' => $sv12, 'etape' => 'validation')); ?>

    <?php include_partial('document_validation/validation', array('validation' => $validation)); ?>

    <form name="sv12_recapitulatif" method="POST" action="<?php echo url_for('sv12_validation', $sv12); ?>" >

        <h3>Récapitulatif</h3>
        <?php include_partial('totaux', array('sv12' => $sv12)); ?>

        <h3>Mouvements</h3>
        <?php include_partial('mouvements', array('mouvements' => $mouvements)); ?>

        <div class="row" style="margin-top: 20px;">
            <div class="col-xs-4 text-left">
                <a tabindex="-1" href="<?php echo url_for('sv12_update', $sv12); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Étape précédente</a>
            </div>
            <div class="col-xs-4 text-center">
                <a href="<?php echo url_for('sv12_etablissement', $sv12->getEtablissementObject()); ?>" class="btn btn-default">Enregistrer le brouillon</a>
            </div>
            <div class="col-xs-4 text-right">
                <button type="submit" class="btn btn-success">Terminer la saisie <span class="glyphicon glyphicon-ok"></span></button>
            </div>
        </div>
    </form>
</section>
