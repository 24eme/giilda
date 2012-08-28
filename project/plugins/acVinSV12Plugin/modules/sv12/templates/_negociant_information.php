<ul>
    <li>
        <span>Négociant :</span>
        
        <span><?php echo $etablissement->identifiant; ?></span>
    </li>
    <li>
        <span>CVI :</span>
        
        <span><?php echo $etablissement->cvi; ?></span>
    </li>
    <li>
        <span>Commune :</span>
        
        <span><?php echo $etablissement->siege->commune; ?></span>
    </li>
</ul>