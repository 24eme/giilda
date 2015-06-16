<!-- #principal -->
<div id="principal" class="clearfix">

    <h2 class="titre_principal">télédéclaration</h2>

    <div id="mon_espace" >
       <div class="cols">
           <div class="col_50">
                <div class="block_teledeclaration espace_contrat">
                   <div class="title">ESPACE CONTRAT</div>
                   <div class="panel">
                       <ul>
                           <li>Vous avez <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>">3 contrats</a> en attente de signature</li>
                           <li>Vous avez <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>">3 contrats</a> en brouillon</li>
                       </ul>
                       <div class="acces">
                           <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>" class="btn_majeur">Acceder aux contrat</a>
                       </div>
                   </div>
               </div>
           </div>
            <div class="col_50">
                <div class="block_teledeclaration espace_drm">
                    <div class="title">ESPACE DRM / DRA</div>
                    <div class="panel">
                        <ul>
                            <li class="text-error">Votre DRM : <a href="<?php echo url_for('drm_societe', array('identifiant' => $identifiant)); ?>">Mars 2015</a> est incomplète et non-validée</li>
                        </ul>
                        <div class="acces">
                            <a class="btn_majeur" href="<?php echo url_for('drm_societe', array('identifiant' => $identifiant)); ?>">DRM</a>
                        </div>
                    </div>
                </div>
            </div>
       </div>

       <div class="block_teledeclaration espace_contrat">
           <div class="title">ESPACE CONTRAT</div>
           <div class="panel">
               <ul>
                   <li>Vous avez <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>">3 contrats</a> en attente de signature</li>
                   <li>Vous avez <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>">3 contrats</a> en brouillon</li>
               </ul>
               <div class="acces">
                   <a href="<?php echo url_for('vrac_societe', array('identifiant' => $identifiant)); ?>" class="btn_majeur">Acceder aux contrat</a>
               </div>
           </div>
        </div>
    </div>

</div>




