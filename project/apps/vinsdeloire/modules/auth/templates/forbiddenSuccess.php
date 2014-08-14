<section id="principal">
<h2>Vous n'avez pas l'autorisation pour accéder à cette page</h2>
<?php if($sf_user->hasTeledeclarationVrac()):
     $identifiantCompte = $sf_user->getCompte()->identifiant; 
    ?>
<div class="text-center">
    <p>Vous allez être automatiquement rediriger vers l'accueil.</p>
</div>
    
<script>
window.setInterval(function(){
   // window.location.assign("https://teledeclaration.vinsvaldeloire.pro/contrats/societe/"+<?php echo $identifiantCompte; ?>);
}, 5000);

</script>
    
<?php endif; ?>
</section>