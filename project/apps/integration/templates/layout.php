<?php
	unlink(sfConfig::get('sf_web_dir').'/css/compile.css');
	$lessFile = sfConfig::get('sf_web_dir').'/css/compile.less';
	$cssFile = sfConfig::get('sf_web_dir').'/css/compile.css';
	lessc::ccompile($lessFile, $cssFile);
?>
<!doctype html>
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]><html class="no-js ie6 ielt7 ielt8 ielt9" lang="fr"><![endif]-->
<!--[if IE 7 ]><html class="no-js ie7 ielt8 ielt9" lang="fr"><![endif]-->
<!--[if IE 8 ]><html class="no-js ie8 ielt9" lang="fr"><![endif]-->
<!--[if IE 9 ]><html class="no-js ie9" lang="fr"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="fr"><!--<![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<head>
	<?php include_title() ?>
	
	<meta charset="utf-8">
	<?php include_http_metas() ?>
    <?php include_metas() ?>
	
	<?php include_stylesheets() ?>
    <?php include_javascripts() ?>
	
	<script type="text/javascript">
		var langueSite = "fr";
		var jsPath = "/js/";
		var ajaxPath = "../ajax.php";
	</script>
</head>

<body>

	<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
	<!--[if lte IE 7 ]>
	<div id="message_ie6">
		<div class="contenu">
			<p><strong>Vous utilisez un navigateur obsolète depuis près de 10 ans !</strong> Il est possible que l'affichage du site soit fortement altéré par l'utilisation de celui-ci.</p>
		</div>
	</div>
	<![endif]-->
	<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
	
	<!-- #header -->
	<header id="header">
		<div class="contenu">
			<h1 id="logo">
				<a href="#" title="<?php echo sfConfig::get('app_titre_site') ?> - Retour à l'accueil">
					<img src="/images/visuels/logo_vinsdeloire.png" alt="<?php echo sfConfig::get('titre_site') ?>" />
				</a>
			</h1>
			
			<nav id="navigation">
				<ul>
					<li><a href="#">DRM</a></li>
					<li class="actif"><a href="#">Contrats</a></li>
					<li><a href="#">Déclaration des volumes</a></li>
					<li><a href="#">Facturation</a></li>
					<li><a href="#">Contacts</a></li>
					<li><a href="#">Import VR</a></li>
					<li><a href="#">SV12</a></li>
					<li><a href="#">Stocks</a></li>
					<li><a href="#">Relance</a></li>
					<li><a href="#">Facture</a></li>
				</ul>
			</nav>
			
			<div id="actions_utilsateur">
				<a href="#" class="admin">Admin</a>
				<a href="#" class="deconnexion">Déconnexion</a>
			</div>
		</div>
	</header>
	<!-- fin #header -->
	
	<!-- #contenu -->
	<div id="contenu">
		<?php echo $sf_content ?>
	</div>
	<!-- fin #contenu -->

	<?php foreach(sfConfig::get('app_javascripts_footer') as $js_footer): ?>
		<script type="text/javascript" src="/js/<?php echo $js_footer; ?>"></script>
	<?php endforeach; ?>

</body>
</html>