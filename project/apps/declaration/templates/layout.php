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
    <link href="/css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<?php 
    $idBody = ($sf_user->hasCredential('teledeclaration'))? "teledeclaration" : "app_transaction_".sfConfig::get('app_instance');
    ?><body id="<?php echo $idBody; ?>">

            <?php include_partial('global/header'); ?>

            <!-- fin #header -->
            <?php
            if ($sf_user->hasFlash('global_error'))
                echo '<div style="margin-bottom: 20px;margin-left: auto; margin-right: auto; width: 700px;" class="global_error"><p><span>' . $sf_user->getFlash('global_error') . "</span></p></div>";
            ?>

    <div id="main" class="container">
        <?php echo $sf_content ?>
    </div>
    <?php include_partial('global/footer'); ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/components/jquery/jquery.js"></script>
    <script src="/components/bootstrap/bootstrap.js"></script>
  </body>
</html>
