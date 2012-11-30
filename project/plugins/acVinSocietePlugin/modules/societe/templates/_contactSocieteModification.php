<div id="contact_societe_modification" class="section_label_maj">
    <h2>Coordonnées de la société </h2>
      <?php
        echo $contactSocieteForm->renderHiddenFields();
        echo $contactSocieteForm->renderGlobalErrors();
        ?>
        <div class="section_label_maj" id="adresse">
            <?php echo $contactSocieteForm['adresse']->renderError(); ?>
            <?php echo $contactSocieteForm['adresse']->renderLabel(); ?>
            <?php echo $contactSocieteForm['adresse']->render(); ?>
        </div>
        <div class="section_label_maj" id="adresse_complementaire">
            <?php echo $contactSocieteForm['adresse_complementaire']->renderLabel(); ?>
            <?php echo $contactSocieteForm['adresse_complementaire']->render(); ?>
            <?php echo $contactSocieteForm['adresse_complementaire']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="code_postal">
            <?php echo $contactSocieteForm['code_postal']->renderLabel(); ?>
            <?php echo $contactSocieteForm['code_postal']->render(); ?>
            <?php echo $contactSocieteForm['code_postal']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="commune">
            <?php echo $contactSocieteForm['commune']->renderLabel(); ?>
            <?php echo $contactSocieteForm['commune']->render(); ?>
            <?php echo $contactSocieteForm['commune']->renderError(); ?>
        </div>                
        <div class="section_label_maj" id="cedex">
            <?php echo $contactSocieteForm['cedex']->renderLabel(); ?>
            <?php echo $contactSocieteForm['cedex']->render(); ?>
            <?php echo $contactSocieteForm['cedex']->renderError(); ?>
        </div>                 
        <div class="section_label_maj" id="pays">
            <?php echo $contactSocieteForm['pays']->renderLabel(); ?>
            <?php echo $contactSocieteForm['pays']->render(); ?>
            <?php echo $contactSocieteForm['pays']->renderError(); ?>
        </div>                
        <div class="section_label_maj" id="email">
            <?php echo $contactSocieteForm['email']->renderLabel(); ?>
            <?php echo $contactSocieteForm['email']->render(); ?>
            <?php echo $contactSocieteForm['email']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="telephone_bureau">
            <?php echo $contactSocieteForm['telephone_bureau']->renderLabel(); ?>
            <?php echo $contactSocieteForm['telephone_bureau']->render(); ?>
            <?php echo $contactSocieteForm['telephone_bureau']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="telephone_mobile">
            <?php echo $contactSocieteForm['telephone_mobile']->renderLabel(); ?>
            <?php echo $contactSocieteForm['telephone_mobile']->render(); ?>
            <?php echo $contactSocieteForm['telephone_mobile']->renderError(); ?>
        </div>
        <div class="section_label_maj" id="fax">
            <?php echo $contactSocieteForm['fax']->renderLabel(); ?>
            <?php echo $contactSocieteForm['fax']->render(); ?>
            <?php echo $contactSocieteForm['fax']->renderError(); ?>
        </div>
<!--        <div class="section_label_maj" id="tags">
            <?php //echo $contactSocieteForm['tags']->renderLabel(); ?>
            <?php //echo $contactSocieteForm['tags']->render(); ?>
            <?php //echo $contactSocieteForm['tags']->renderError(); ?>
        </div>-->
</div>
