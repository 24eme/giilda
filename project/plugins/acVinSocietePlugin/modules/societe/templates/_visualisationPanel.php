<div id="detail_societe" class="form_section ouvert">
    <h3>Détail de la société </h3>  
    <div class="form_contenu">
        <div class="form_ligne">
            <label for="raison_sociale">
                Nom de la société : 
            </label>
            <?php echo $societe->raison_sociale; ?>
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
                Statut : 
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

                <?php echo $societe->numero_compte_fournisseur; ?>
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
            <label for="tva_intracom">
                TVA intracom : 
            </label>
            <?php echo $societe->tva_intracom; ?>
        </div>
        <div class="form_ligne">
            <label for="commentaire">
                commentaire : 
            </label>
            <?php echo $societe->commentaire; ?>
        </div>
    </div>
</div>
