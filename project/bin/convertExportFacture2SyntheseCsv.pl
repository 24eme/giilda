#!/usr/bin/perl
$debitcredit = 9;
$montant = 10;
$factureid = 13;
$nom = 15;
$drmid = 19;
<STDIN>;
while(<STDIN>) {
	chomp;
	@field = split/;/ ;
	next if ($field[$debitcredit] eq 'DEBIT');
	if ($field[$drmid] =~ /DRM-0*(\d+)01-(\d{4})(\d\d)/ ) {
	    $tiers = $1;
	    $campagne = ($2 - 1).'-'.$2;
	    if ($3 > 07) {
		$campagne = $2.'-'.($2 + 1);
	    }
	}
	$sumerize{$tiers.';'.$field[$nom].';'.$campagne} += $field[$montant];
}

foreach $k (keys %sumerize) {
    print $k.';'.$sumerize{$k}.";\n";
    $c = $k;
    $c =~ s/.*;//;
    $sum{$c} += $sumerize{$k};
}
$sum = 0;
foreach $k (keys %sum) {
    print "TOTAL Campagne;Total Campagne;".$k.';'.$sum{$k}.";\n";
    $sum += $sum{$k};
}
print "TOTAL;TOTAL;TOTAL;$sum;\n";
