<section id="contenu"> 
    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'ajouts_liquidations', 'pourcentage' => '10')); ?>
    
    <section id="principal">
        <h2>Détails des contrats</h2>
        <div id="application_dr">
                <div id="contenu_onglet">
                    <form method="post" action="<?php echo url_for('drm_mouvements_generaux_produits_update', $drm) ?>">
                        <p class="intro">Contrats (vrac ou conditionné)</p>
                        <ul id="list_contrats">
                            <li id="contrat">
                                <div id="contrat_poduit_nom">
                                </div>
                                <div  id="contrat_num">
                                </div>
                                <div id="contrat_vol">
                                </div>
                                <div id="contrat_dateEnlevement">
                                </div>
                            </li>
                        </ul>
                    </form>
                    <div id="ajout_contrats">
                        <a href="<?php echo url_for('drm_mouvements_generaux_stock_epuise', $drm) ?>" id="stock_epuise" style="float:none; margin: 0 0 15px 0;">Stock épuisé</a>
                    </div>                            
                    <div id="somme_hecto">

                    </div>
                </div>
        </div>
    </section>
</section>