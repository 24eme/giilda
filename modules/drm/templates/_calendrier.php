<div class="section_label_maj" id="calendrier_drm">
    <label for="">Campagne Viticole</label>
    <select name="" id="">
        <option value=""><?php echo $campagne ?></option>
    </select>
    <div class="bloc_form">
        <div class="ligne_form ligne_compose">
            <ul class="liste_mois">
                <?php foreach($calendrier->getPeriodes() as $periode): ?>
                    <?php include_partial('drm/calendrierItem', array('calendrier' => $calendrier, 'periode' => $periode)); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
