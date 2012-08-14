<!-- #header -->
<header id="header">
		<div class="contenu">
			<h1 id="logo">
				<a title="Vins de Loire - Retour à l'accueil" href="#">
					<img alt="" src="/images/visuels/logo_vinsdeloire.png">
				</a>
			</h1>

			<nav id="navigation">
				<ul>
					<li class="<?php echo preg_match('/^drm/', $sf_request->getParameter('module')) ? "actif" : null ?>"><a href="<?php echo url_for('drm'); ?>">DRM</a></li>
                    <li class="<?php echo preg_match('/^vrac/', $sf_request->getParameter('module')) ? "actif" : null ?>"><a href="<?php echo url_for('vrac'); ?>">Contrats</a></li>
					<li><a href="<?php echo url_for('facture'); ?>">Facture</a></li>
					<li><a href="#">Contacts</a></li>
					<li><a href="#">Import VR</a></li>
					<li><a href="#">SV12</a></li>
					<li><a href="#">Stocks</a></li>
					<li><a href="#">Relance</a></li>
				</ul>
			</nav>
			
			<div id="actions_utilsateur">
				<a class="admin" href="#">Admin</a>
				<a class="deconnexion" href="#">Déconnexion</a>
			</div>
		</div>
	</header>
<!-- fin #header -->