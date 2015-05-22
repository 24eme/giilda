<?php
use_helper("Generation");
?>
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
    <div class="ligne_form ligne_form_alt">
        <span>
            <label>Statut : </label>
            <?php echo $generation->statut; ?>
            (Mis à jour le <?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->date_maj); ?>)
        </span>
    </div>
  <?php if ($generation->message) : ?>
    <div class="ligne_form ligne_form_alt">
        <span>
            <label>Message : </label>
            <?php echo $generation->message; ?>
        </span>
    </div>
  <?php endif; ?>
    <div class="ligne_form">
        <span>
            <label>Arguments :</label>
        </span>
    </div>
    <?php 
    $cpt=0;
    foreach ($generation->arguments as $key => $argument) : ?>
    <div class="ligne_form ligne_form_alt">
        <span>
            <label><?php echo getLabelForKeyArgument($key); ?> </label>
            <?php 
            echo $argument;
            ?>
        </span>
    </div>
     <?php 
     $cpt++;
     endforeach; 
     ?>
    
</div>
<?php if ($generation->statut == GenerationClient::GENERATION_STATUT_GENERE && count($generation->fichiers)) : ?>
    <h2>Liste des <?php echo $type; ?> : </h2>
    <fieldset id="generation_documents">
        <table id="generation_documents_table" class="table_recap">
            <thead>
                <tr>
                    <th>Titre</th>
       <?php //                    <th>Impression</th> ?>
                    <th>Téléchargement</th>
                </tr>
            </thead>
            <tbody class="generation_documents_tableBody">
                <?php foreach ($generation->fichiers as $chemin => $titre) :
                    ?>
                    <tr id="generation">
                        <td class="">
                            <?php echo $titre; ?>
                        </td>
<?php /*
                        <td class="">
                            <a href="#" class="btn_vert btn_majeur" >impr.</a>
                        </td>
      */?>
                        <td class="">
                            <a href="<?php echo urldecode($chemin); ?>" class="btn_jaune btn_majeur" >téléch.</a>
                        </td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </tbody>
        </table> 
    </fieldset>
<?php endif; ?>
<div class="btn_etape" id="ligne_btn">
    <a href="<?php
echo (isset($identifiant)) ?
        url_for(strtolower($type) . '_etablissement', array('identifiant' => $identifiant)) :
        url_for(strtolower($type));
?>" class="btn_etape_prec"><span>Retour</span></a> &nbsp; 
<?php if($generation->statut == GenerationClient::GENERATION_STATUT_ENATTENTE): ?>
<script><!--
	      window.setTimeout("window.location.reload()", 30000);
--></script>
<?php endif; ?>
<?php if($generation->statut == GenerationClient::GENERATION_STATUT_ENERREUR): ?>
   <a class="btn_vert btn_majeur" href="<?php echo url_for('generation_reload', array('type_document' => $generation->type_document, 'date_emission' => $generation->date_emission)); ?>">Relancer</a>
<?php endif; ?>
<?php if($generation->statut == GenerationClient::GENERATION_STATUT_GENERE): ?>
   <a class="btn_rouge btn_majeur" href="<?php echo url_for('generation_delete', array('type_document' => $generation->type_document, 'date_emission' => $generation->date_emission)); ?>">Supprimer</a>
<?php endif; ?>
</div>