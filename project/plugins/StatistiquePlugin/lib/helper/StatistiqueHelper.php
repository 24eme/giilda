<?php

function formatNumber($number) {
	return ($number && $number != 0)? number_format($number, 2, ',', '') : null;
}