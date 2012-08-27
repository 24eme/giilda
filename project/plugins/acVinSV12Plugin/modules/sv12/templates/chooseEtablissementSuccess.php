<div id="contenu" class="sv12">    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <?php include_component('sv12', 'chooseEtablissement'); ?>
            
            <fieldset id="history_sv12">
                <legend>Déclaration SV12 en cours de Saisie</legend>
                    <table class="table_recap">
                    <thead>
                    <tr>
                        <th>Date - Version </th>
                        <th>N° Sv12</th>
                        <th>Négociant</th>
                        <th>CVI</th>
                        <th>Commune</th>                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historySv12->rows as $sv12h) : 
                            $elt = $sv12h->getRawValue()->value;
                            $num_contrat = preg_replace('/VRAC-/', '', $elt[SV12Client::SV12_VIEWHISTORY_DATESAISIE]);
                        ?>
                        <tr>
                            <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_DATESAISIE]; ?></td>
                            <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_ID]; ?></td>
                            <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_NOM]; ?></td>
                            <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_CVI]; ?></td>
                            <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_COMMUNE]; ?></td>
                        </tr>
                        <?php
                        endforeach;
                        ?>   
                    </tbody>
                    </table>
            </fieldset>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
    
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
    </aside>
    <!-- fin #colonne -->
</div>