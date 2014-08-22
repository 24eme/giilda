<section id="principal">
    <h2>Oups... une erreur est survenue sur le serveur</h2>
    <div id="modification_compte" class="fond" >
        <div class="presentation clearfix">
            Nous sommes désolés et vous prions de nous contacter afin de nous signaler et détailler le probleme.<br /> 

            Nous tâcherons alors de le résoudre dans les plus brefs délais.
        </div>
        <div class="ligne_btn">
            <a href="<?php echo url_for('homepage'); ?>" class=" btn_majeur btn_modifier modifier" alt="Retour" style="cursor: pointer; float: left;">Retour</a>
        </div>
    </div>
    <br/>
    <br/>
    <h2><?php echo $exception->getMessage(); ?></h2>

</center>
</section>
