<section id="principal" class="drm_delete">
    <h2>Import d'une DRM</h2>
    <ul>
    <?php foreach ($csvFile->getCsv() as $csvRow) :?>
        <li>
            <?php foreach ($csvRow as $csvValue): ?>
            <span><?php echo ($csvValue)? $csvValue : '(vide)' ?></span>&nbsp;
              <?php endforeach;  ?>
        </li>
  <?php  endforeach; ?>
    </ul>
    <br>
     <h2>Rapport d'erreurs</h2>
     <ul>
    <?php foreach ($erreurs as $key => $erreur) :?>
        <li>
            <span><?php echo $key.' : '.$erreur ?></span>&nbsp;
             
        </li>
  <?php  endforeach; ?>
    </ul>
    <br>
</section>