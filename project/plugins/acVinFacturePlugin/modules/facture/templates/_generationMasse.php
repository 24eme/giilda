<?php $url_post = (isset($massive) && $massive)? url_for('facture_generation') : ""; ?>
<form method="post" action="<?php echo $url_post; ?>" role="generationForm" class="form-horizontal">
        <?php echo $generationForm->renderHiddenFields(); ?>
        <?php echo $generationForm->renderGlobalErrors(); ?>
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="form-group <?php if ($generationForm["modele"]->hasError()): ?>has-error<?php endif; ?>">
                    <?php echo $generationForm["modele"]->renderError() ?>
                    <?php echo $generationForm["modele"]->renderLabel("Type de facture", array("class" => "col-xs-4 control-label")); ?>
                    <div class="col-xs-8">
                        <?php echo $generationForm["modele"]->render(array("class" => "form-control input-lg")); ?>
                    </div>
                </div>
                <div class="form-group <?php if ($generationForm["date_facturation"]->hasError()): ?>has-error<?php endif; ?>">
                    <?php echo $generationForm["date_facturation"]->renderError(); ?>
                    <?php echo $generationForm["date_facturation"]->renderLabel("Date de facturation", array("class" => "col-xs-4 control-label")); ?>
                    <div class="col-xs-8 date-picker">
                            <?php echo $generationForm["date_facturation"]->render(array("class" => "form-control input-lg", "placeholder" => "Date de facturation")); ?>
                    </div>
                </div>    
                <div class="form-group <?php if ($generationForm["message_communication"]->hasError()): ?>has-error<?php endif; ?>">
                    <?php echo $generationForm["message_communication"]->renderError(); ?>
                    <?php echo $generationForm["message_communication"]->renderLabel("Cadre de communication", array("class" => "col-xs-4 control-label")); ?>
                    <div class="col-xs-8">
                       
                            <?php echo $generationForm["message_communication"]->render(array("class" => "form-control input-lg")); ?>
                      
                    </div>
                </div>                
                <div class="form-group text-right">
                    <div class="col-xs-6 col-xs-offset-6">
                        <button class="btn btn-success btn-lg btn-block btn-upper" type="submit">Générer la facture</button>
                    </div>
                </div>
            </div>
        </div>
</form> 