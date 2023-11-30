<header id="header">
  <div class="header-top">
    <div class="container">
      <div class="row">
        <div class="col-xs-2">
          <div class="logo-site">
            <a href="/"><img src="/images/logo_site_bivc.png" alt="Logo Sancerre" height="100px"/></a>
          </div>
        </div>
        <div class="col-xs-8 text-center">
          <h1>
              Bureau Interprofessionnel des Vins du Centre-Loire</br>
              <small>Espace déclaratif professionnel</small>
          </h1>
        </div>
        <div class="col-xs-2">
            <div id="gotoodg" style="background-color: #f3eadb; margin-top: 25px; width: 115px; margin-left: 60px; padding-right: 25px;" class="text-right">
                <?php $urlodg = "https://odg.aoc-centre-loire.fr/"; if ($sf_user->isAuthenticated()) { $url = "https://viticonnect.net/cas/sancerre/login?service=".$urlodg; }?>
                <div class="clear:both">
                <span style="display: block; height: 100%; top: 50%; position: absolute; left: 175px;"> &gt; </span>
                <a style="display: block;" href="<?php echo $urlodg ; ?>"> Accès<br/>Portail ODG</a>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
            <?php include_component('common', 'nav'); ?>
      </div>
  </nav>
</header>
