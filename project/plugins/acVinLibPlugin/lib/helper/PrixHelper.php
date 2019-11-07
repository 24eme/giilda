<?php
   function ttc($p) {
        return $p + $p * 0.2;
    }

function echoTtc($prixHt,$tva = 0.2)
{
  if (is_null($prixHt))
		return null;
  echo sprintf("%01.02f", round(($prixHt + $prixHt * $tva), 2));
}
