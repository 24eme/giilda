<a href="#notice_popup_content" style="display: none;" class="popup_link_notices"></a>
<div style="display:none">
    <div id="notice_popup_content" class="notice_contenu">
        <h2>Notices </h2>
        <br/>
        <p> Ci dessous vous pouvez télécharger la notice correspondante à votre métier :  
        </p>
           <br/>
        <p>
            <a href="<?php echo url_for('vrac_notice', array('type' => SocieteClient::SUB_TYPE_VITICULTEUR)); ?>" class="lien_telechargement">Notice utilisateur - viticulteurs</a>
        </p>
        <p>
            <a href="<?php echo url_for('vrac_notice', array('type' => SocieteClient::SUB_TYPE_NEGOCIANT)); ?>" class="lien_telechargement">Notice utilisateur - négociants</a>
        </p>
        <p>
            <a href="<?php echo url_for('vrac_notice', array('type' => SocieteClient::SUB_TYPE_COURTIER)); ?>" class="lien_telechargement">Notice utilisateur - courtiers</a>
        </p>
        <div class="ligne_btn">
            <a id="notice_popup_close" class="btn_rouge btn_majeur annuler" style="float: left;" href="#" >Annuler</a>
        </div>
    </div>
</div>