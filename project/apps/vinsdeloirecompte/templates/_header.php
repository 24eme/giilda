<!-- #header -->
<header id="header">
		<div class="contenu">
			<h1 id="logo">
				<a title="Vins de Loire - Retour à l'accueil" href="#">
					<img alt="" src="/images/visuels/logo_vinsdeloire_new.png">
				</a>
			</h1>

			<?php include_component('global', 'nav'); ?>
			
			<div id="actions_utilsateur">
				<a class="deconnexion" href="<?php echo url_for('@ac_vin_logout') ?>">Déconnexion</a>
			</div>
		</div>
	</header>
<!-- fin #header -->