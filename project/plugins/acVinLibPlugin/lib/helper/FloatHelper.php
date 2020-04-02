<?php

function sprintFloat($float, $format = "%01.05f")
{
	if (is_null($float))
		return null;
	if (preg_match('/f$/', $format))
    return preg_replace('/00$/', '', sprintf($format, $float));
  return $float;
}

function sprintFloatFr($float, $format = "%01.02f")
{

  return preg_replace('/\./', ',', sprintFloat($float, $format));
}

function echoFloat($float)
{
  echo sprintFloat($float);
}

function echoLongFloat($float)
{
  echo sprintFloat($float, "%01.05f");
}

function echoLongFloatFr($float)
{
  echo sprintFloatFr($float, "%01.05f");
}

function echoFloatFr($float)
{
  echo sprintFloatFr($float);
}

function echoSignedFloat($float)
{
  echo ($float>0)? '+'.sprintFloat($float) : sprintFloat($float);
}

function echoArialFloat($float) {
    echo number_format($float, 2, '.', ' ');
}

function getArialFloat($float) {
    return number_format($float, 2, '.', ' ');
}
