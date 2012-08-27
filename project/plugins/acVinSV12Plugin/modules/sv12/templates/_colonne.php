<!-- #colonne -->
<aside id="colonne">

    <div class="bloc_col" id="contrat_aide">
        <h2>Aide</h2>

        <div class="contenu">
            <ul>
                <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                <li class="assistance"><a href="#">Assistance</a></li>
                <li class="contact"><a href="#">Contacter le support</a></li>
            </ul>
        </div>
    </div>

    <div class="bloc_col" id="infos_contact">
        <h2>Infos contact</h2>

        <div class="contenu">
            <ul>
                <li id="infos_contact_negociant">
                    <a href="#">Coordonnées négociant</a>
                    <ul>
                        <li class="nom"><?php echo $negociant->nom; ?></li>
                        <li class="tel">00 00 00 00 00</li>
                        <li class="fax">00 00 00 00 00</li>
                        <li class="email"><a href="mailto:email@email.com">email@email.com</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- fin #colonne -->
</aside>