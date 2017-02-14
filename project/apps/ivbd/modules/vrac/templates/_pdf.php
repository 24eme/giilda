<?php
 if ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE) {
 	include_partial('vrac/ivbd_bouteille', array('vrac' => $vrac)); 	
 } elseif ($vrac->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS || $vrac->type_transaction == VracClient::TYPE_TRANSACTION_MOUTS) {
 	include_partial('vrac/ivbd_raisin', array('vrac' => $vrac));
 } else {
 	include_partial('vrac/ivbd_vrac', array('vrac' => $vrac));
 }