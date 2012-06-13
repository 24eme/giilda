<?php if($num_etape == 0 && $vrac->etape == 0) echo "<a href=".url_for('vrac_nouveau').">";
      else
            if($vrac->etape >= $num_etape) echo "<a href=".url_for($url_etape,$vrac).">";
            echo '<li class="';
            if($actif == $num_etape+1) echo 'actif';
                    else if($actif > $num_etape) echo 'passe';
            echo '">';
            echo '<span>1. <span>'.$label.'</span></span>';     
            echo '</li>';
            if($vrac->etape >= $num_etape) echo '</a>';
    if($num_etape == 0 && $vrac->etape == 0) echo "</a>";
            
?>     
