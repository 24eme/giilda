<?php
$nouveau = is_null($vrac);
$pourcentage = ($vrac->etape) * 25;

?>
<aside id="colonne">	
		<div id="contrat_progression" class="bloc_col">
			<h2>Campagne viticole : 2011-2012</h2>
			
			<div class="contenu">
				<p><strong><?php echo $pourcentage;?>%</strong> du contrat a été saisi</p>
				
				<div id="barre_progression">
					<span style="width: <?php echo $pourcentage."%";?>;"></span>
				</div>
			</div>
		</div>
		
		<div id="contrat_aide" class="bloc_col">
			<h2>Aide</h2>
			
			<div class="contenu">
				<ul>
					<li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
					<li class="assistance"><a href="#">Assistance</a></li>
					<li class="contact"><a href="#">Contacter le support</a></li>
				</ul>
			</div>
		</div>
		
		<div id="infos_contact" class="bloc_col">
			<h2>Infos contact</h2>
			
			<div class="contenu">
				<ul>
					<li id="infos_contact_vendeur">
						<a href="#">Coordonnées vendeur</a>
						<ul>
							<li class="nom"><?php echo (!$nouveau)? $vrac->vendeur->nom : 'Nom du vendeur'; ?></li>
							<li class="tel">00 00 00 00 00</li>
							<li class="fax">00 00 00 00 00</li>
							<li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
						</ul>
					</li>
					<li id="infos_contact_acheteur">
						<a href="#">Coordonnées acheteur</a>
						<ul>
							<li class="nom"><?php echo (!$nouveau)? $vrac->acheteur->nom : 'Nom du acheteur'; ?></li>
							<li class="tel">00 00 00 00 00</li>
							<li class="fax">00 00 00 00 00</li>
							<li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
						</ul>
					</li>
					<li id="infos_contact_mendataire">
						<a href="#">Coordonnées mandataire</a>
						<ul>
							<li class="nom"><?php echo (!$nouveau && $vrac->mandataire)? $vrac->mandataire->nom : 'Nom du mandataire'; ?></li>
							<li class="tel">00 00 00 00 00</li>
							<li class="fax">00 00 00 00 00</li>
							<li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	
	</aside>