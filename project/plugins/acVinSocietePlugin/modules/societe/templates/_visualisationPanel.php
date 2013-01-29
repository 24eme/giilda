<?php
use_helper('Date');
?>
<div id="detail_societe" class="form_section ouvert">
    <h3>Détail de la société </h3>  
    <div class="form_contenu">
		<div class="form_modifier">
			<a href="<?php echo url_for('societe_modification', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_modifier">Modifier</a>
		</div>
        <div class="form_ligne">
            <label for="raison_sociale">
                Nom de la société : 
            </label>
            <?php echo $societe->raison_sociale; ?>
        </div>
        <div class="form_ligne">
            <label for="date_modification">
                Dernière date de modification : 
            </label>
            <?php echo format_date($societe->date_modification,'dd/MM/yyyy'); ?>
        </div>
        <div class="form_ligne">
            <label for="raison_sociale_abregee">
                Abrégé : 
            </label>
            <?php echo $societe->raison_sociale_abregee; ?>
        </div>
        <div class="form_ligne">
            <label for="statut">
                Statut : 
            </label>
            <?php echo $societe->statut; ?>
        </div>
        <div class="form_ligne">
            <label for="statut">
                Type : 
            </label>
            <?php echo $societe->type_societe; ?>
        </div>    
        <?php if ($societe->code_comptable_client) : ?>
            <div class="form_ligne">
                <label for="code_comptable_client">
                    Numero de compte client : 
                </label>
                <?php echo $societe->code_comptable_client; ?>
            </div>  
        <?php endif; ?>
        <?php if ($societe->code_comptable_fournisseur) : ?>
            <div class="form_ligne"> 
                <label for="code_comptable_fournisseur">
                    Numero de compte Fournisseur : 
                </label>

                <?php echo $societe->code_comptable_fournisseur; ?>
            </div>  
        <?php endif; ?>
        <div class="form_ligne">
            <label for="siret">
                SIRET : 
            </label>
            <?php echo $societe->siret; ?>
        </div>                
        <div class="form_ligne">
            <label for="code_naf">
                Code Naf : 
            </label>
            <?php echo $societe->code_naf; ?>
        </div>
        <?php
        foreach ($societe->enseignes as $key => $enseigne) :
            ?>
            <div class="form_ligne">
                <label for="enseigne">
                    Enseigne : 
                </label>
                <?php echo $enseigne->label; ?>
            </div>
            <?php
        endforeach;
        ?>
        <div class="form_ligne">
            <label for="no_tva_intracommunautaire">
                TVA intracom : 
            </label>
            <?php echo $societe->no_tva_intracommunautaire; ?>
        </div>
        <div class="form_ligne">
            <label for="commentaire">
                commentaire : 
            </label>
            <pre class="commentaire"><?php echo $societe->commentaire;?></pre>
        </div>
    </div>
</div>
