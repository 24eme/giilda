<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <!-- Bootstrap core CSS -->
    <link href="/components/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="/components/select2/select2.css" rel="stylesheet">
    <link href="/components/select2/select2-bootstrap.min.css" rel="stylesheet">
    <link href="/components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/components/vins/vins.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/components/jquery/jquery.js"></script>
  </head>
<?php 
    $idBody = ($sf_user->hasCredential('teledeclaration'))? "teledeclaration" : "app_transaction_".sfConfig::get('app_instance');
    ?><body id="<?php echo $idBody; ?>">

    <?php include_partial('global/header'); ?>

    <div id="main" class="container">
        <?php echo $sf_content ?>
    </div>

    <div id="ajax-modal" class="modal"></div>

    <?php include_partial('global/footer'); ?>
    <script src="/components/bootstrap/bootstrap.js"></script>
    <script src="/components/select2/select2.min.js"></script>
    <script src="/components/moment/moment-with-locales.min.js"></script>
    <script src="/components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/plugins/jquery.plugins.min.js"></script>
    <script src="/js/lib/jquery-ui-1.8.21.min.js"></script>
    <script src="/js/ajaxHelper.js"></script>
    <script src="/js/form.js"></script>
    <script src="/js/colonnes.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/conditionnement.js"></script>
    <script src="/js/vrac.js"></script>
    <script src="/js/drm.js"></script>
    <script src="/js/contacts.js"></script>
    <script src="/js/facture.js"></script>
    <script src="/js/stats.js"></script>
    <script src="/js/lib/jquery.sticky.js"></script>
  </body>
</html>
