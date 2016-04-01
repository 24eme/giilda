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

print "code tiers;nom;campagne;montant HT;\n";
foreach $k (keys %sumerize) {
    $s = $sumerize{$k};
    $s =~ s/\./,/;
    print $k.';'.$s.";\n";
    $c = $k;
    $c =~ s/.*;//;
    $sum{$c} += $sumerize{$k};
}
$sum = 0;
foreach $k (keys %sum) {
    $s = $sum{$k};
    $s =~ s/\./,/;
    print "TOTAL Campagne;Total Campagne;".$k.';'.$s.";\n";
    $sum += $sum{$k};
}
$sum =~ s/\./,/;
print "TOTAL;TOTAL;TOTAL;$sum;\n";
