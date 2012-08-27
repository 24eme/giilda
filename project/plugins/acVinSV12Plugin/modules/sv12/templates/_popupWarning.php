
<div id="sv12_popup_warning">
<h2>Action impossible ! </h2>
<div class="sv12_popupWarningContent">
    <p> Une déclaration SV12 a déjà été saisie pour cet opérateur sur la campagne viticole <?php echo ($sv12[1]-1).'-'.$sv12[1];?>,
        vous pouvez la modifier en cliquant ici : </p>
    <a href=""><?php echo $sv12[6];?></a>
    <p>Il sera possible de saisir les déclarations SV12 de la campagne viticole <?php echo ($sv12[1]-1).'-'.$sv12[1];?> à partir du 1er septembre <?php echo ($sv12[1]); ?>.
    </p>
    <input type="button" class="btn_majeur btn_rouge" value="Fermer" />
</div>
</div>

<a id="sv12_popup_warning_trigger" href="#sv12_popup_warning"></a>