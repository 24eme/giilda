<?php $args = array('q' => $q); ?>
<section id="principal">
<div id="recherche_contact" class="section_label_maj"><form>
   Recherche d'un contact&nbsp;: <input type="text" name="q" value="<?php echo $q; ?>" role="textbox" aria-autocomplete="list" aria-haspopup="true"> <input type="submit" value="Recherche"/>
</div>
<p><?php echo $nb_results; ?> résultat(s) trouvé(s) (page <?php echo $current_page; ?> sur <?php echo $last_page; ?>)</p>
<p><a  class="btn_majeur btn_excel" href="<?php echo url_for('compte_search_csv', $args); ?>">Télécharger le tableur</a></p>
<table><?php 
$cpt = 0;
foreach($results as $res) {
   $data = $res->getData();
   $class = ($cpt % 2) ? ' class="even"' : '';
   echo '<tr'.$class.'>';
   echo "<td>".$data['nom_a_afficher']."</td>";
   echo "<td>".$data['adresse'].', '.$data['code_postal'].' '.$data['commune']."</td>";
   echo "<td>".$data['telephone_bureau'].' '.$data['telephone_mobile'].' '.$data['telephone_perso'].' '.$data['fax']."</td>";
   echo "<td>".$data['email']."</td>";
   echo '<td><a href="'.url_for('compte_visualisation', array('identifiant' => $data['identifiant'])).'">détail</a></td>';
   echo "</tr>";
}
?></table>
<?php $args = array('q' => $q); ?>
<a href="<?php echo url_for('compte_search', $args); ?>"> <<- </a>
<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
<a href="<?php echo url_for('compte_search', $args); ?>"> <- </a>
<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
<a href="<?php echo url_for('compte_search', $args); ?>"> -> </a>
<?php $args['page'] = $last_page; ?>
<a href="<?php echo url_for('compte_search', $args); ?>"> ->> </a>
</form>
</section>
<?php
slot('colButtons'); 
?>
 <div class="bloc_col" >
            <h2>Actions</h2>

            <div class="contenu">
                <ul>
                    <li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('societe');?>">Accueil des contacts</a></li>
                </ul>
            </div>
        </div>
    <?php
end_slot();
?>
