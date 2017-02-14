<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h2 class="panel-title">Activation de votre espace de télédeclaration (Convention d'adhésion)</h2></div>
            <div class="panel-body">
                <form action="<?php echo url_for("compte_teledeclarant_cgu") ?>" method="post">
                    <p>
                        Votre interprofession met à disposition de ses ressortissants un portail de télédéclaration pour les DRM et les contrats d’achats&nbsp;:&nbsp;«&nbsp;ivsopro.com&nbsp;».
                    </p>
                    <p>
                        Pour activer votre espace de téléclaration, vous devez prendre connaissance et accepter la convention d'adhésion aux télédéclarations avec l'IVSO :  <a target="_blank" href="">consulter la convention d'adhésion</a>.
                        <br /><br />
                    </p>

                    <div class="checkbox">
                      <label>
                        <input required="required" type="checkbox" name="accepter" value="1">
                        J'accepte la <a target="_blank" href="">convention d'adhésion</a> aux télédéclarations
                      </label>
                    </div>
                    <div class="row" >
                        <div class="col-xs-12 text-right">
                            <button type="submit" class="btn btn-success">Continuer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
