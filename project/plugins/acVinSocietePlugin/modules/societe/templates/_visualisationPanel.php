<?php
use_helper('Date');
?>
<div class="col-xs-12">
    <div class="panel panel-default">
    <div class="panel-heading">Détail de la société</div>
    <div class="panel-body">
        <?php if($modification || $reduct_rights) : ?>
		<div class="form_modifier">
                    <a href="<?php echo url_for('compte_search', array('q' => str_replace('COMPTE-', '', $societe->compte_societe))); ?>" class="btn_majeur btn_nouveau">Ajouter un tag</a>
                        <a href="<?php echo url_for('societe_modification', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_modifier">Modifier</a>
		</div>
        <?php endif; ?>
        <div class="form_ligne">
            <label for="raison_sociale">
                Nom de la société : 
            </label>
            <?php echo $societe->raison_sociale; ?>
        </div>
        <?php if($societe->date_creation) : ?>
         <div class="form_ligne">
            <label for="date_creation">
                Date de création : 
            </label>
            <?php echo format_date($societe->date_creation,'dd/MM/yyyy'); ?>
        </div>
        <?php endif; ?>   
        <div class="form_ligne">
            <label for="date_modification">
                Dernière date de modification : 
            </label>
            <?php echo format_date($societe->date_modification,'dd/MM/yyyy'); ?>
        </div>
        <?php if($societe->raison_sociale_abregee) : ?>
            <div class="form_ligne">
                <label for="raison_sociale_abregee">
                    Abrégé : 
                </label>
                <?php echo $societe->raison_sociale_abregee; ?>
            </div>
        <?php endif; ?>        
        <div class="form_ligne">
            <label for="statut">
                Statut : 
            </label>
            <?php echo $societe->statut; ?>
        </div>
        <div class="form_ligne">
            <label for="type_societe">
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
        <?php if ($societe->exist('type_fournisseur') && count($societe->type_fournisseur)) : ?>
            <div class="form_ligne"> 
                <label for="type_fournisseur">
                    Type de Fournisseur : 
                </label>
                <?php foreach ($societe->type_fournisseur as $type_fournisseur) : ?>
                    <?php echo $type_fournisseur; ?>&nbsp;
                <?php endforeach; ?>
            </div>  
        <?php endif; ?>
        <?php if ($societe->siret) : ?>
            <div class="form_ligne">
                <label for="siret">
                    SIRET : 
                </label>
                <?php echo $societe->siret; ?>
            </div>     
        <?php endif; ?>
        <?php if ($societe->code_naf) : ?>
        <div class="form_ligne">
            <label for="code_naf">
                Code Naf : 
            </label>
            <?php echo $societe->code_naf; ?>
        </div>
        <?php endif; ?> 
        
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
        <?php if ($societe->no_tva_intracommunautaire) : ?>
            <div class="form_ligne">
                <label for="no_tva_intracommunautaire">
                    TVA intracom : 
                </label>
                <?php echo $societe->no_tva_intracommunautaire; ?>
            </div>
        <?php endif; ?>
        <?php if ($societe->commentaire) : ?>        
        <div class="form_ligne">
            <label for="commentaire">
                commentaire : 
            </label>
            <pre class="commentaire"><?php echo $societe->commentaire;?></pre>
        </div>
        <?php endif; ?>
        </div>
    </div>
</div>
