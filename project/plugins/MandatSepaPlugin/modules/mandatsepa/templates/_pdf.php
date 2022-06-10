<style>
.tableAlt {
    border: 1px solid #bec6d0;
}
.h3Alt {
    background-color: #bec6d0; color: #000; font-weight: bold;
}
</style>

<table border="0"><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>

<span class="h3Alt">&nbsp;Objet&nbsp;</span><br/>
<table class="tableAlt">
  <tr>
    <td>
      <table border="0">
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td><?php echo $mandatSepa->mention_autorisation ?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td><?php echo $mandatSepa->mention_remboursement ?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td><?php echo $mandatSepa->mention_droits ?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>

<table border="0"><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>

<span class="h3Alt">&nbsp;Créancier&nbsp;</span><br/>
<table class="tableAlt">
  <tr>
    <td>
      <table border="0">
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr>
            <td>Identifiant (ICS) : <strong><?php echo $mandatSepa->creancier->identifiant_ics ?></strong></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $mandatSepa->creancier->nom ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $mandatSepa->creancier->adresse ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;<?php echo $mandatSepa->creancier->code_postal ?>&nbsp;<?php echo $mandatSepa->creancier->commune ?></td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>

<table border="0"><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>

<span class="h3Alt">&nbsp;Débiteur&nbsp;</span><br/>
<table class="tableAlt">
  <tr>
    <td>
      <table border="0">
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr>
            <td>Identifiant (RUM) : <strong><?php echo $mandatSepa->debiteur->identifiant_rum ?></strong></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $mandatSepa->debiteur->nom ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $mandatSepa->debiteur->adresse ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $mandatSepa->debiteur->code_postal ?>&nbsp;<?php echo $mandatSepa->debiteur->commune ?></td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>

<table border="0"><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>

<span class="h3Alt">&nbsp;Informations bancaires&nbsp;</span><br/>
<table class="tableAlt">
  <tr>
    <td>
      <table border="0">
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr>
            <td>Prélèvement <?php echo strtolower(MandatSepaClient::getFrequencePrelevementLibelle($mandatSepa->debiteur->frequence_prelevement)) ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>IBAN : <?php echo $mandatSepa->getIbanFormate() ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>BIC : <?php echo $mandatSepa->debiteur->bic ?></td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>

<p style="text-align: right;">
  Le <?php echo $mandatSepa->getDateFr() ?>
</p>

<table border="0">
  <tr>
    <td>&nbsp;</td>
    <td>
      <table class="tableAlt">
        <tr><td>&nbsp;Signature du débiteur :</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
      </table>
    </td>
  </tr>
</table>
