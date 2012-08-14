<fieldset id="historique_drm">
    <legend>Historique des DRMs de l'opérateur</legend>
    <nav>
        <ul>
            <li class="actif"><span>Vue calendaire</span></li>
            <li><a href="">Vue des mouvements</a></li>
        </ul>
    </nav>
    <div class="section_label_maj" id="calendrier_drm">
        <label for="">Campagne Viticole</label>
        <select name="" id="">
            <option value="">2011 - 2012</option>
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
</fieldset>