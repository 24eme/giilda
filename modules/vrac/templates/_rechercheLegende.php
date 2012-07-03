<?php
use_helper('Vrac');
$rechercheMode = (isset($rechercheMode) && $rechercheMode);
?> 
<div class="legende">    
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_RAISINS)))? 'class="actif"' : ''; ?> >
        <?php 
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_RAISINS)).'">';
        ?>
        <span class="type_raisins">type_raisins</span><span class="legende_type_texte">Raisins</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_MOUTS)))? 'class="actif"' : ''; ?> >
        <?php
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_MOUTS)).'">';
        ?>
        <span class="type_mouts">type_mouts</span><span class="legende_type_texte">Mouts</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_VIN_VRAC)))? 'class="actif"' : ''; ?> >
        <?php 
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_VIN_VRAC)).'">'; 
        ?>
        <span class="type_vin_vrac">type_vin_vrac</span><span class="legende_type_texte">Vrac</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)))? 'class="actif"' : ''; ?> >
        <?php 
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'type' => VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE)).'">';
        ?>
        <span class="type_vin_bouteille">type_vin_bouteille</span><span class="legende_type_texte">Bouteilles</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <br />
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::STATUS_CONTRAT_SOLDE)))? 'class="actif"' : ''; ?> >
        <?php 
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_SOLDE)).'">';
        ?>
        <span class="statut statut_solde"></span><span class="legende_statut_texte">Soldé</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::STATUS_CONTRAT_NONSOLDE)))? 'class="actif"' : ''; ?> >
        <?php
        if($rechercheMode)
        echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_NONSOLDE)).'">'; 
        ?>
        <span class="statut statut_non-solde"></span><span class="legende_statut_texte">Non-soldé</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    <div <?php echo (($rechercheMode) && (isARechercheParam($actif,VracClient::STATUS_CONTRAT_ANNULE)))? 'class="actif"' : ''; ?> >
        <?php
        if($rechercheMode)
            echo '<a href="'.url_for('vrac_recherche',array('identifiant'=>$identifiant, 'statut' => VracClient::STATUS_CONTRAT_ANNULE)).'" >';
        ?>
        <span class="statut statut_annule"></span><span class="legende_statut_texte">Annulé</span>
        <?php if($rechercheMode) echo '</a>'; ?>
    </div>
    
<!--</div>
<div class="legende">-->

</div>