<div id="generation_infos" class="bloc_form">    
        <div class="ligne_form">
            <span>
                  <label>N° Generation :</label>
                  <?php echo $generation->identifiant; ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span>
                <label>Date : </label>
                <?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->date_emission); ?>
            </span>
        </div>
        <div class="ligne_form">
            <span>
                <label>Nombre de documents : </label>
                <?php echo $generation->nb_documents; ?>
            </span>
        </div>
        <div class="ligne_form">
            <span>
                <label>Statut : </label>
                <?php echo $generation->statut; ?>
            </span>
        </div>
    </div>
<?php if($generation->statut == GenerationClient::GENERATION_STATUT_GENERE) : ?>
<h2>Liste des factures/avoirs : </h2>
<fieldset id="generation_documents">
        <table id="generation_documents_table" class="table_recap">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Impression</th>
                <th>Téléchargement</th>
            </tr>
        </thead>
        <tbody class="generation_documents_tableBody">
            <?php foreach ($generation->documents as $document) :                
            ?>
            <tr id="generation">
                <td class="">
                    <?php echo ''; ?>
                </td>
                <td class="">
                    <?php echo ''; ?>
                </td>
                
                <td class="">
                    <?php echo 'nb_pages'; ?>
                </td>
                
                <td class="">
                    <a href="#" class="btn_vert btn_majeur" >impr.</a>
                </td>
                
                <td class="">
                    <a href="<?php url_for('facture_pdf',
                            array('identifiant' => str_replace('ETABLISSEMENT-', '', $facture->key[FactureEtablissementView::KEYS_CLIENT_ID]),
                                  'factureid' => str_replace('FACTURE-' . $etablissement->identifiant . '-', '', $facture->key[FactureEtablissementView::KEYS_FACTURE_ID]))); ?>" class="btn_jaune btn_majeur" >téléch.</a>
                </td>
              </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>
<?php endif; ?>