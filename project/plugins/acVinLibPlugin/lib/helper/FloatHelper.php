<?php

function formatFloat($number, $defaultDecimalFormat = null, $maxDecimalAuthorized = null, $format = null) {
    
    return FloatHelper::getInstance()->format($number, $defaultDecimalFormat, $maxDecimalAuthorized, $format);
}

function formatFloatFr($number, $defaultDecimalFormat = null, $maxDecimalAuthorized = null, $format = null) {
    
    return FloatHelper::getInstance()->formatFr($number, $defaultDecimalFormat, $maxDecimalAuthorized, $format);
}

function sprintFloat($number, $format = "%01.02f") 
{
    return formatFloat($number, null, null, $format);
}

function sprintFloatFr($float, $format = "%01.02f")
{
    return formatFloatFr($number, null, null, $format);
}

function echoFloat($number) 
{
    echo formatFloat($number);
}

function echoFloatFr($number)
{
    echo formatFloatFr($number);
}

function echoLongFloat($number) 
{
    echo formatFloat($number, 4, 4);
}

function echoLongFloatFr($number)
{
    echo formatFloatFr($number, 4, 4);
}

function echoSignedFloat($number) 
{
    echo ($number>0)? '+'.formatFloat($number) : formatFloat($number);
}

function echoArialFloat($number) {
    
    echo number_format($number, 2, '.', ' ');
}

function getArialFloat($number) {
    
    return number_format($number, 2, '.', ' ');
}