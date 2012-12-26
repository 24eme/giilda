<?php $uniq_id = uniqid(); ?>

<div class="hamza_style" style="margin-top: 30px;">
    <div class="autocompletion_tags" data-table="<?php echo $table_selector ?>" data-source="sources_<?php echo $uniq_id ?>">
    <label><?php echo $consigne ?></label>
    
    <ul class="tags"></ul>
    <!--
    <button class="btn_majeur btn_rechercher" type="button">Rechercher</button>
    -->
    </div>
</div>

<script type="text/javascript"> 
    var sources_<?php echo $uniq_id ?> = <?php echo json_encode($mots->getRawValue()) ?>;
</script>