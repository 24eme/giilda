<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <?php include_partial('headerRevendication', array('revendication' => $revendication,'actif' => 3)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            
            <h2>Volumes revendiqués</h2>
            <fieldset id="revendication_volume_revendiques_edition">
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>CVI</th>
                            <th>Nom</th>
                            <th>Produit</th>
                            <th style="width: 100px;">Volume (en hl)</th>
                            <th>Editer</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($revendication->datas as $cvi => $etb) : 
                        include_partial('revendication/edition_tableau_etablissement',array('etb' => $etb, 'retour' => 'odg'));
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </fieldset>
        </section>
    </section>
</div>
