<section id="principal">
<h2>Vous n'avez pas l'autorisation pour accéder à cette page</h2>
<?php if($sf_user->hasTeledeclarationVrac()): ?>
<div class="text-center">
    <p>Vous allez être automatiquement rediriger vers l'accueil.</p>
</div>

<script>
window.setInterval(function(){
    window.location.assign("<?php echo sfContext::getInstance()->getRouting()->generate('vrac_societe',array('identifiant' => $sf_user->getCompte()->identifiant),true); ?>");
}, 50000);

</script>

<?php endif; ?>
</section>
