<ol id="rail_etapes">
    <?php $cpt_etape = 1 ; ?>
    <?php if(isset($isTeledeclarationMode) && $isTeledeclarationMode) : ?>    
   <li class="actif">
        <a href="#">
            <strong><span style="cursor: default;"><?php echo $cpt_etape; ?> </span>
                Produits 
            </strong>    </a>    
    </li>
    <?php $cpt_etape++; ?>
    <?php endif; ?>
    <li class="">
        <a href="#">
            <strong><span style="cursor: default;"><?php echo $cpt_etape++; ?> </span>
                Saisie 
            </strong>    </a>    
    </li>
    <li class="">
        <a>
            <span style="cursor: default;"><?php echo $cpt_etape; ?> </span>
            Validation 
        </a>    
    </li>
</ol>