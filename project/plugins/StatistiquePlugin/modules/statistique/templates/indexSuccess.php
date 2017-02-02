<ol class="breadcrumb">
    <li><a href="<?php echo url_for('statistiques') ?>" class="active">Recherche</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
    	<h2>Moteur de recherche</h2>
        <div class="col-sm-3 col-sm-offset-3">
        	<div class="panel panel-default nouv_campagne">
    			<div class="panel-heading text-center">DRM</div>
    			<div class="panel-body text-center">
    				<p style="font-size: 30px;"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></p>
					<a class="btn btn-default btn-block " href="<?php echo url_for('statistiques_drm') ?>">Accéder</a>
            	</div>
            </div>
        </div>
        <div class="col-sm-3">
        	<div class="panel panel-default nouv_campagne">
    			<div class="panel-heading text-center">Contrats</div>
    			<div class="panel-body text-center">
    				<p style="font-size: 30px;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></p>
					<a class="btn btn-default btn-block " href="<?php echo url_for('statistiques_vrac') ?>">Accéder</a>
            	</div>
            </div>
        </div>
    </div>
</div>
