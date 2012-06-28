<?php
use_helper('Vrac');
?> 
<div class="legende">
    <div <?php echo (isARechercheParam($actif,VracClient::STATUS_CONTRAT_SOLDE))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_SOLDE)); ?>">
        <span class="statut statut_solde"></span><span class="legende_statut_texte">Soldé</span>
        </a>
    </div>
    <div <?php echo (isARechercheParam($actif,VracClient::STATUS_CONTRAT_NONSOLDE))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE)); ?>">
        <span class="statut statut_non-solde"></span><span class="legende_statut_texte">Non-soldé</span>
        </a>
    </div>
    <div <?php echo (isARechercheParam($actif,VracClient::STATUS_CONTRAT_ANNULE))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_ANNULE)); ?>">
        <span class="statut statut_annule"></span><span class="legende_statut_texte">Annulé</span>
        </a>
    </div>
    
    <div <?php echo (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_RAISINS))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_RAISINS)); ?>">
        <span class="type_raisins">type_raisins</span><span class="legende_type_texte">Raisins</span>
        </a>
    </div>
    <div <?php echo (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_MOUTS))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_MOUTS)); ?>">
        <span class="type_mouts">type_mouts</span><span class="legende_type_texte">Mouts</span>
        </a>
    </div>
    <div <?php echo (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_VIN_VRAC))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_VIN_VRAC)); ?>">
        <span class="type_vin_vrac">type_vin_vrac</span><span class="legende_type_texte">Vrac</span>
        </a>
    </div>
    <div <?php echo (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))? 'class="actif"' : ''; ?> >
        <a href="<?php echo url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)); ?>">
        <span class="type_vin_bouteille">type_vin_bouteille</span><span class="legende_type_texte">Bouteilles</span>
        </a>
    </div>
<!--</div>
<div class="legende">-->

</div>