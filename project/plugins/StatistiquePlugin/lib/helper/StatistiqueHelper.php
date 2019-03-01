<?php

function formatNumber($number, $round = 0) {
	return ($number && $number != 0)? number_format($number, $round, ',', '') : null;
}

function formatNumberPourcent($number, $round = 0){
	$n = formatNumber($number, $round);
	if(floatval($n) > 0 ){
		return "+".$n;
	}
	return $n;
}

function getEvol($last, $current) {
	if(!$last && !$current){
		return "";
	}
	if(!$current){
		return "- infini";
	}
	if(!$last){
		return "+ infini";
	}

	$last = str_replace(',', '.', $last);
	$current = str_replace(',', '.', $current);
	return formatNumberPourcent((($current - $last) / $last) * 100, 2);
}
