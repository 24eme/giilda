    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Gestion des contacts</h2>
			<form action="<?php echo url_for('societe'); ?>" method="post">
				<div class="section_label_maj" id="recherche_societe">
					<?php echo $contactsForm['identifiant']->renderError(); ?>
					<label for="contacts_identifiant">Recherche d'un société, d'un etablissement ou d'un interlocuteur :</label>
					<?php echo $contactsForm['identifiant']->render(); ?>
					<button id="btn_rechercher" type="submit">Chercher</button>
                                        
                                        <label for="contacts_all">Inclure les contacts suspendus </label>
                                        <input id="contacts_all" name="contacts_all" type="checkbox" value="1"/>
				</div>
                                
			</form>
        </section>
    </section>
<?php
slot('colButtons'); 
?>
 <div class="bloc_col" >
            <h2>Actions</h2>

            <div class="contenu">
                <ul>
                    <li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('compte_search');?>">Recherche avancée</a></li>
                    <li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('societe_creation');?>">Créer une société</a></li>
                </ul>
            </div>
        </div>
    <?php
end_slot();
?>


       
    