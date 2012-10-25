<ul id="recap_infos_header">
    <li>
        <span>Campagne viticole :</span>
        <?php echo $sv12->campagne ?>
    </li>
    <li>
        <span>Numéro d'archive</span>
        <?php echo $sv12->numero_archive ?>
    </li>
    <li>
        <span>Négociant :</span>
        <?php echo $sv12->declarant->nom; ?>
    </li>
    <li>
        <span>CVI :</span>
        <?php echo $sv12->declarant->cvi; ?>
    </li>
    <li>
        <span>Commune :</span>
        <?php echo $sv12->declarant->commune; ?>
    </li>
</ul>