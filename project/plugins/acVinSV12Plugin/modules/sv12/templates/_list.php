 <fieldset id="history_sv12">
    <legend>Déclaration SV12 en cours de Saisie</legend>
        <table class="table_recap">
        <thead>
        <tr>
            <th>Date - Version </th>
            <th>N° Sv12</th>
            <th>Négociant</th>
            <th>Commune</th>
            <th>Statut</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $item) : 
                $elt = $item;
                $id = $elt[SV12Client::SV12_VIEWHISTORY_ID];
                $negociant_id = $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_ID];
                $periode_version = SV12Client::getInstance()->buildPeriodeAndVersion($elt[SV12Client::SV12_VIEWHISTORY_PERIODE], $elt[SV12Client::SV12_VIEWHISTORY_VERSION]);
            ?>
            <tr>
                <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_DATESAISIE]; ?></td>
                <td>
                    <?php if($elt[SV12Client::SV12_VIEWHISTORY_STATUT] == SV12Client::SV12_STATUT_VALIDE): ?>
                        <?php echo link_to($id, '@sv12_visualisation?identifiant='.$negociant_id.'&periode_version='.$periode_version) ; ?>
                    <?php elseif($elt[SV12Client::SV12_VIEWHISTORY_STATUT] == SV12Client::SV12_STATUT_BROUILLON): ?>
                        <?php echo link_to($id, '@sv12_update?identifiant='.$negociant_id.'&periode_version='.$periode_version) ; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo url_for(array('sf_route' => 'sv12_etablissement', 'identifiant' => $negociant_id)) ?>">
                        <?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_NOM]; ?> (N°CVI: <?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_CVI]; ?>)
                    </a>
                </td>
                <td><?php echo $elt[SV12Client::SV12_VIEWHISTORY_NEGOCIANT_COMMUNE]; ?></td>
                <td>
                    <?php if($elt[SV12Client::SV12_VIEWHISTORY_STATUT] == SV12Client::SV12_STATUT_VALIDE): ?>
                        <?php echo link_to('Visualiser', '@sv12_visualisation?identifiant='.$negociant_id.'&periode_version='.$periode_version) ; ?>
                    <?php elseif($elt[SV12Client::SV12_VIEWHISTORY_STATUT] == SV12Client::SV12_STATUT_BROUILLON): ?>
                        <?php echo link_to('Continuer', '@sv12_update?identifiant='.$negociant_id.'&periode_version='.$periode_version) ; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>   
        </tbody>
        </table>
</fieldset>