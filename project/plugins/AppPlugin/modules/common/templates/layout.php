<?php
$gitcommit = file_get_contents('../../.git/ORIG_HEAD');
 ?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="<?php echo public_path("/favicon.ico") ?>" />
        <?php include_stylesheets() ?>
        <!-- Bootstrap core CSS -->
        <?php if(file_exists(sfConfig::get('sf_web_dir')."/css/bootstrap_".sfConfig::get('sf_app').'.css')): ?>
            <link href="<?php echo public_path("/css/bootstrap_".sfConfig::get('sf_app').".css").'?'.$gitcommit; ?>" rel="stylesheet">
        <?php else: ?>
            <link href="<?php echo public_path("/css/bootstrap.css") ?>" rel="stylesheet">
        <?php endif; ?>
        <link href="<?php echo public_path("/components/select2/select2.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <link href="<?php echo public_path("/components/select2/select2-bootstrap.min.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <link href="<?php echo public_path("/components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <link href="<?php echo public_path("/components/vins/vins.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <link href="<?php echo public_path("/css/style.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <?php if(file_exists(sfConfig::get('sf_web_dir')."/css/style_".sfConfig::get('sf_app').'.css')): ?>
            <link href="<?php echo public_path("/css/style_".sfConfig::get('sf_app').".css").'?'.$gitcommit; ?>" rel="stylesheet">
        <?php endif; ?>
        <link href="<?php echo public_path("/js/lib/leaflet/leaflet.css").'?'.$gitcommit; ?>" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?php echo public_path("/components/jquery/jquery.js") ?>"></script>
    </head>
    <body>
        <?php include_partial('common/header'); ?>

        <div id="content" style="min-height: 945px; padding-bottom: 20px;">
            <?php if(sfConfig::get('app_instance') == 'preprod' ): ?>
              <div><p style="color:red; text-align:center; font-weight: bold;">Preproduction (la base est succeptible d'être supprimée à tout moment)</p></div>
            <?php endif; ?>
            <?php if(isset($global_message)) { echo $global_message ; } ?>
    		<div class="container">
            <?php echo $sf_content ?>
            </div>

            <div id="ajax-modal" class="modal"></div>
            <?php include_partial('common/footer'); ?>
        </div>

        <script src="<?php echo public_path("/components/bootstrap/bootstrap.js") ?>"></script>
        <script src="<?php echo public_path("/components/select2/select2.min.js") ?>"></script>
        <script src="<?php echo public_path("/components/moment/moment-with-locales.min.js") ?>"></script>
        <script src="<?php echo public_path("/components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js") ?>"></script>
        <script src="<?php echo public_path("/js/plugins/jquery.plugins.min.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/lib/jquery-ui-1.8.21.min.js") ?>"></script>
        <script src="<?php echo public_path("/js/ajaxHelper.js") ?>"></script>
        <script src="<?php echo public_path("/js/form.js") ?>"></script>
        <script src="<?php echo public_path("/js/colonnes.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/main.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/teledeclaration.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/conditionnement.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/vrac.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/drm.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/contacts.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/facture.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/stats.js?$gitcommit") ?>"></script>
        <script src="<?php echo public_path("/js/lib/jquery.sticky.js") ?>"></script>
        <script src="<?php echo public_path("/js/lib/leaflet/leaflet.js") ?>"></script>
  </body>
</html>
