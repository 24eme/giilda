<h2>Alerte</h2>
<fieldset>
    <section>
        <div>
            <span>
                Type d'alerte : 
                <label>
                    <?php echo AlerteClient::$alertes_libelles[$alerte->type_alerte]; ?>
                </label>
            </span>
        </div>
        <div>
            <span>
                Libellé : 
                <label>

                    <?php echo $alerte->id_document; ?>
                </label>
            </span>
        </div>
        <div>
            <span>
                Opérateur : 
                <label>
                    <?php echo  $alerte->declarant_nom. ' ('.$alerte->identifiant . ') '; ?>
                </label>
            </span>
        </div>
    </section>
</fieldset>
