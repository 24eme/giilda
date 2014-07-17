<!-- #header -->
<header id="header">
		<div class="contenu">
			<h1 id="logo">
				<a title="Vins de Loire - Retour à l'accueil" href="#">
					<img src="/images/visuels/logo_vinsdeloire_new.png" alt="" />
				</a>
			</h1>

			<div class="conteneur_nav">
				<span class="baseline">Espace des professionels du Vignoble de Val de Loire</span>		
				<?php include_component('global', 'nav'); ?>
			</div>

			<button class="btn_menu" type="button">Menu</button>
			
			<div id="actions_utilsateur">
   				<?php if ($sf_user->hasCredential('admin')) : ?>
					<a class="admin" href="<?php echo url_for('produits') ?>">Admin</a>
   				<?php endif; ?>
   				<a href="#">Mon compte</a>
				<a class="deconnexion" href="<?php echo url_for('@ac_vin_logout') ?>">Déconnexion</a>
			</div>
		</div>
	</header>
<!-- fin #header -->