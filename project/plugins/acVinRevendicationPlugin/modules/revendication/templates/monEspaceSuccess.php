<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">

            <h2>Volumes revendiqués</h2>
            <fieldset id="revendication_volume_revendiques_edition">
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>CVI</th>
                            <th>Nom</th>
                            <th>Produit</th>
                            <th style="width: 100px;">Volume (en hl)</th>
                            <th>Editer</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if($revendication_etablissement)
                        include_partial('revendication/edition_tableau_etablissement',array('etb' => $revendication_etablissement, 'retour' => 'etablissement'));
                        else echo "<tr><td> Aucune revendications trouvées</td></tr>"
                    ?>
                    </tbody>
                </table>
            </fieldset>
        </section>
    </section>
</div>
