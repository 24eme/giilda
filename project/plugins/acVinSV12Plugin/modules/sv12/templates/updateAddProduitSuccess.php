<?php include_partial('sv12/breadcrumb', array('sv12' => $sv12)); ?>

<section id="principal" class="sv12">
    <?php include_partial('sv12/etapes', array('sv12' => $sv12)); ?>
    <form class="form-horizontal" id="form_produit_declaration" method="post" action="<?php echo url_for("sv12_update_addProduit", $sv12) ?>">
        <?php echo $form->renderGlobalErrors(); ?>
        <?php echo $form->renderHiddenFields(); ?>
        <div class="form-group">
            <?php echo $form['hashref']->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
            <div class="col-sm-6">
                <?php echo $form['hashref']->renderError(); ?>
                <?php echo $form['hashref']->render(array("autofocus" => "autofocus")); ?>
            </div>
        </div>
            <div class="form-group">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <div class="checkbox">
                      <label>
                        <?php echo $form['withviti']->render(); ?>
                        Affecter l'enlevement à un viti
                      </label>
                    </div>
                </div>
            </div>
        <div class="lienviti">
          <?php if($raisinetmout) : ?>
            <div class="form-group">
                <?php echo $form['raisinetmout']->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
                <div class="col-sm-10">
                    <?php echo $form['raisinetmout']->renderError(); ?>
                    <?php echo $form['raisinetmout']->render(); ?>
                </div>
            </div>
          <?php endif; ?>

            <div class="form-group">
                <?php echo $form['identifiant']->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
                <div class="col-sm-6">
                    <?php echo $form['identifiant']->renderError(); ?>
                    <?php echo $form['identifiant']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form['volume']->renderLabel(null, array("class" => "col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form['volume']->renderError(); ?>
                    <div class="input-group">
                      <?php echo $form['volume']->render(); ?>
                      <div class="input-group-addon">hl</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 text-left">
                <a tabindex="-1" href="<?php echo url_for("sv12_update", $sv12) ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Annuler</a>
            </div>
            <div class="col-xs-4 text-center">
            </div>
            <div class="col-xs-4 text-right">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </div>
        </div>
    </form>
</section>
<script type="text/javascript">
    $('#sv12_add_produit_withviti').change(function () {
        if ($(this).is(':checked')) {
            $('.lienviti').show();
        } else {
            $('.lienviti').hide();
        }
    });
</script>
