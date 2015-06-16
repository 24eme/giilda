<div class="section_label_maj" id="calendrier_drm">
   <form method="POST">
   <?php echo $formCampagne->renderGlobalErrors() ?>
   <?php echo $formCampagne->renderHiddenFields() ?>
   <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
   </form>
    <div class="bloc_form">
        <div class="ligne_form ligne_compose">
            <ul class="liste_mois">
                <?php foreach($calendrier->getPeriodes() as $periode): ?>
                    <?php include_partial('drm/calendrierItem', array('calendrier' => $calendrier, 'periode' => $periode)); ?>
                <?php endforeach; ?>
                <li class="bloc_mois valide_campagne">
                  <p class="mois">Janvier</p>

                  <div class="mois_infos">
                    <ul class="liste_etablissements clearfix">
                      <li class="valide_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 1</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 1</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="valide_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 2</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 2</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="valide_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 3</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 3</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>

                <li class="bloc_mois valide_papier_campagne">
                  <p class="mois">Février</p>

                  <div class="mois_infos">
                    <ul class="liste_etablissements clearfix">
                      <li class="valide_papier_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 1</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 1</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="valide_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 2</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 2</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="valide_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 3</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 3</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>

                <li class="bloc_mois attente_campagne">
                  <p class="mois">Mars</p>

                  <div class="mois_infos">
                    <ul class="liste_etablissements clearfix">
                      <li class="attente_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 1</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 1</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="attente_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 2</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 2</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="attente_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 3</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 3</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>

                <li class="bloc_mois nouv_campagne">
                  <p class="mois">Avril</p>

                  <div class="mois_infos">
                    <ul class="liste_etablissements clearfix">
                      <li class="nouv_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 1</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 1</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="nouv_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 2</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 2</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                      <li class="nouv_etablissement">
                        <button class="btn_etablissement" type="button">Etablissement 3</button>

                        <div class="etablissement_tooltip">
                          <p class="etablissement_nom">Etablissement 3</p>
                          <p>Etat : <span class="statut">Validée</span></p>
                          <a href="#" class="action">Voir la drm</a>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>

                <li class="bloc_mois nouv_campagne">
                  <p class="mois">Mai</p>

                  <div class="mois_infos">
                    <div class="nouv_etablissement">
                      <p class="etablissement_nom">Etablissement 1</p>
                      <p>Etat : <span class="statut">Validée</span></p>
                      <a href="#" class="action">Voir la drm</a>
                    </div>
                  </div>
                </li>
            </ul>
        </div>
    </div>
</div>
