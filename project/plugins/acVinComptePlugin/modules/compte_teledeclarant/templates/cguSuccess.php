<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Condition général</h3></div>
            <div class="panel-body">
                <form action="<?php echo url_for("compte_teledeclarant_cgu") ?>" method="post">
                    <div class="checkbox">
                      <label>
                        <input required="required" type="checkbox" name="accepter" value="1">
                        Accepter les conditions
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
