<div id="ds_infos" class="bloc_form">   
        <div class="ligne_form">
            <span>
                  <label>Numéro d'archive :</label>
                  <?php display_field($ds,'numero_archive'); ?>
            </span>
        </div> 
        <div class="ligne_form ligne_form_alt">
            <span>
                  <label>N° DS :</label>
                  <?php display_field($ds,'_id'); ?>
            </span>
        </div>
        <div class="ligne_form">
            <span>
                <label>Campagne viticole : </label>
                <?php display_field($ds,'campagne'); ?>
            </span>
        </div>
        <div class="ligne_form ligne_form_alt">
            <span>
                <label>Etat : </label>
                <?php echo DSClient::getInstance()->getLibelleStatutForHistory($ds->statut); ?>
            </span>
        </div>
    </div>