<div class="hamza_style" style="margin-top: 30px;">
    <div class="autocompletion_tags" data-table="<?php echo $table_selector ?>" data-source="source_tags">
    <label>Saisissez un produit, un type de mouvement, un numéro de contrat, un pays d'export, etc. :</label>
    
    <ul class="tags"></ul>
    <!--
    <button class="btn_majeur btn_rechercher" type="button">Rechercher</button>
    -->
    </div>
</div>

<script type="text/javascript"> 
    var source_tags = <?php echo json_encode($mots->getRawValue()) ?>;
</script>