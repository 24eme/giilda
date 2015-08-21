<section id="principal" class="drm_delete">
    <h2>Import d'une DRM</h2>
    <?php if (count($erreurs)): ?>
        <h2>Rapport d'erreurs</h2>
        <table class="table_recap">
            <thead>
                <tr>                        
                    <th>Numéro de ligne</th>
                    <th>Erreur</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <?php foreach ($erreurs as $erreur) : ?>
                <tr>                        
                    <td><?php echo $erreur->num_ligne; ?></td>
                    <td style="color: darkred"><?php echo $erreur->erreur_csv; ?></td>
                    <td style="color: darkgray"><?php echo $erreur->raison; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <br>
</section>