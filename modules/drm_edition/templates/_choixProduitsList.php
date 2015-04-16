

<div id="col_saisies_cont">
    <table>
        <thead id="table_stocks" class="table_recap">
            <tr>                        
                <th>Produit</th>
                <th>Pas de mouvements</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($produits as $produit): ?>
           <tr>                        
               <td><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
               <td><input type="checkbox" /></td>
            </tr>  
        <?php ?>
    <?php endforeach; ?>
        </tbody>
    </table>
</div>