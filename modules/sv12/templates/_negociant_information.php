<ul id="infos_negociant">
    <li>
        <span>Négociant :</span> <?php echo $etablissement->identifiant; ?>
    </li>
    <li>
        <span>CVI :</span> <?php echo $etablissement->cvi; ?>
    </li>
    <li>
        <span>Commune :</span> <?php echo $etablissement->siege->commune; ?>
    </li>
</ul>