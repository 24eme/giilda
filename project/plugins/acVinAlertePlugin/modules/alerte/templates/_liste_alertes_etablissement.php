        <?php
        use_helper('Date');
        $statutsWithLibelles = AlerteClient::getStatutsWithLibelles();
        ?>

        <?php if (!count($alertesEtablissement)): ?>
          <div class="row">
      			<div class="col-xs-12">
      				<p><strong>
                    Aucune alerte pour cet opérateur
                  </strong></p>
          			</div>
          		</div>

        <?php else: ?>
          <div class="row">
      			<div class="col-xs-12">
            <table class="table table-condensed table-bordered table_selection">
                <thead>
                    <tr>
                        <th class="selecteur"><input type="checkbox" /></th>
                        <th>Date du statut</th>
                        <th>Statut</th>
                        <th>Type d'alerte</th>
                        <th>Document concerné</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alertesEtablissement as $alerte) :

                        $document_link = link_to($alerte->value[AlerteRechercheView::VALUE_LIBELLE_DOCUMENT], 'redirect_visualisation', array('id_doc' => $alerte->value[AlerteRechercheView::VALUE_ID_DOC]));
                        if(($alerte->key[AlerteRechercheView::KEY_TYPE_ALERTE] == AlerteClient::DRM_MANQUANTE) || ($alerte->key[AlerteRechercheView::KEY_TYPE_ALERTE] == AlerteClient::DRA_MANQUANTE)){
                                       $document_link = link_to($alerte->value[AlerteRechercheView::VALUE_LIBELLE_DOCUMENT], 'drm_etablissement', array('identifiant' => $alerte->key[AlerteRechercheView::KEY_IDENTIFIANT_ETB], 'campagne' => $alerte->key[AlerteRechercheView::KEY_CAMPAGNE]));
                                    }
                                    $styleRow = "";
                                $classRow = "";
                                if($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_FERME){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_EN_SOMMEIL){
                                    $styleRow = 'style="opacity: 0.5"';
                                }
                                if(($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_A_RELANCER) || ($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_A_RELANCER_AR)){
                                    $classRow = 'success';
                                }
                                if($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_EN_ATTENTE_REPONSE){
                                    $classRow = 'warning';
                                }
                                 if($alerte->key[AlerteRechercheView::KEY_STATUT] == AlerteClient::STATUT_EN_ATTENTE_REPONSE_AR){
                                     $classRow = 'danger';
                                 }

                    ?>
                        <tr class="<?php echo $classRow; ?>" <?php echo $styleRow; ?> >
                            <td class="selecteur">
                                <?php echo $modificationStatutForm[$alerte->id]->renderError(); ?>
                                <?php echo $modificationStatutForm[$alerte->id]->render() ?>
                            </td>
                            <td>
                                <?php echo format_date($alerte->value[AlerteRechercheView::VALUE_DATE_MODIFICATION], 'dd/MM/yyyy'); ?>
                                (Ouv.: <?php echo format_date($alerte->value[AlerteRechercheView::VALUE_DATE_CREATION], 'dd/MM/yyyy'); ?>)
                            </td>
                            <td><?php echo $statutsWithLibelles[$alerte->key[AlerteRechercheView::KEY_STATUT]]; ?></td>
                            <td><?php
                        echo link_to(AlerteClient::$alertes_libelles[$alerte->key[AlerteRechercheView::KEY_TYPE_ALERTE]], 'alerte_modification', array('type_alerte' => $alerte->key[AlerteRechercheView::KEY_TYPE_ALERTE],
                            'id_document' => $alerte->value[AlerteRechercheView::VALUE_ID_DOC]));
                                ?></td>
                            <td><?php echo $document_link; ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
          </div>
        </div>
<?php endif; ?>
