<?php 


?>

<table>    
    <thead>
        <tr>
            <th>
                Statut
            </th>
            <th>
                N° Contrat
            </th>
            <th>
                Acheteur
            </th>
            <th>
                Vendeur
            </th>
            <th>
                Mandataire
            </th>
            <th>
                Type
            </th>
            <th>
                Produit
            </th>
            <th>
                Vol. com.
            </th>
            <th>
                Vol. enlv.
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vracs->rows as $value) {   
            $elt = $value->getRawValue()->value;
        ?>
        <tr>
            <?php 
            foreach ($elt as $key => $field) {
            ?>
            <th>
                <?php 
                if($key==6) echo is_null($field)? 'NONE' : ConfigurationClient::getCurrent()->get($field)->libelleProduit();
                else echo is_null($field)? 'NONE' : $field;
                ?>
            </th>
            <?php
            }
            ?>
        </tr>
        <?php        
        }
        ?>
    </tbody>
</table>    