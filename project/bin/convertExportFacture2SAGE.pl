#!/usr/bin/perl

use Encode;
use Env;

$verbose = shift;
my $htTvaAGG = $ENV{EXPORT_SAGE_FACTURE_HT_TVA_AGG};

my $totalHt = 0;
my $totalTva = 0;
my @infosHt = ();
my @infosTva = ();

while(<STDIN>) {
	chomp;
	my @field = split/;/ ;
    next if ($field[0] ne 'VEN' && $field[0] !~ /^[02]+$/);
	next if ($field[10] == 0  && $field[14] !~ /TVA/);
	if ($field[14] =~ /TVA/) {
	    $totalTva += $field[10];
	    @infosTva = @field;
        next if ($htTvaAGG == 1);
	}
	if ($field[14] =~ /LIGNE/) {
	    $totalHt += $field[10];
	    @infosHt = @field;
        next if ($htTvaAGG == 1);
	}
	print "Ecriture générale;" if ($verbose);
	printSageEntryFunction(\@field);
    if ($field[14] =~ /TVA/) {
		$montant_tva_isset = 1;
	    $montant_tva = $field[10];
	    $compte_tva = $field[5];
	}
	if ($field[9] =~ /DEBIT/) {
	    $montant_ttc = $field[10];
	    $date = $field[2];
	    $date_echeance = $field[8];
	    $code_client = $field[6];
	}
	if ($montant_tva_isset && $montant_ttc) {
	    print "MRGT;" if ($verbose);
	    print "#MRGT\n";
	    print "\n";
	    print "1\n";
	    print "date;" if ($verbose);
	    print $date_echeance."\n";
	    print "date saisie;" if ($verbose);
	    print $date."\n";
	    print "code client;" if ($verbose);
	    print $code_client."\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "compte général tva;" if ($verbose);
	    print $compte_tva."\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "0\n";
	    print "20,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "montant ht;" if($verbose);
	    $ht = sprintf('%.02f', $montant_ttc - $montant_tva);
	    $ht =~ s/\./,/;
	    print $ht."\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "0,00\n";
	    print "montant tva;" if($verbose);
	    print $montant_tva."\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "code taxe;" if ($verbose);
	    print $field[22];
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "0\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    print "\n";
	    $montant_tva = 0;
	    $montant_ttc = 0;
		$montant_tva_isset = 0;
	}
}
if ($htTvaAGG == 1) {
    $infosHt[10] = $totalHt;
    $infosTva[10] = $totalTva;
    $infosHt[3] = '';
    $infosTva[3] = '';
    $infosHt[15] = 'GLOBAL HT';
    $infosTva[15] = 'GLOBAL TVA';
    $infosHt[12] = '';
    $infosTva[12] = '';
    printSageEntryFunction(\@infosHt);
    printSageEntryFunction(\@infosTva);
}

sub printSageEntryFunction {
    my @field = @{$_[0]};
    my $montant = $field[10];
    $field[10] = sprintf("%.2f", $field[10]);
    $field[10] =~ s/\./,/;
    print "#MECG\n";
	print "code journal;" if ($verbose);
    print $field[0]."\n";
	print "date;" if ($verbose);
	$field[1] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[1]."\n";
    print "date saisie;" if ($verbose);
	$field[2] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[2]."\n";
    print "piece;" if ($verbose);
	print $field[23];
    print "numero de facture;" if ($verbose);
    print $field[3]."\n";
    print "piece tréso;" if ($verbose);
    print $field[11]."\n";
    print "numero compte general;" if ($verbose);
    print $field[5]."\n";
    print "numero compte general contre partie;" if ($verbose);
    print "\n";
    print "numero compte tiers;" if ($verbose);
    print $field[6]."\n";
    print "numero compte tiers contre partie;" if ($verbose);
    print "\n";
    print "intitule;" if ($verbose);
    print encode_utf8(substr(decode_utf8($field[4]), 0, 35))."\n";
    print "numero reglement;" if ($verbose);
    print "\n";
    print "date echeance;" if ($verbose);
	$field[8] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
    print $field[8]."\n";
    print "partie;" if ($verbose);
    print "0,000000\n";
    print "quantite;" if ($verbose);
    print "\n";
    print "numero devis;" if ($verbose);
    print "0\n";
    print "sens (credit = 1 / debit = 0);" if ($verbose);
    if ($field[9] eq 'CREDIT') {
		print "1\n";
	} else {
		print "0\n";
	}
    print "montant;" if ($verbose);
    print $field[10]."\n";
    print "numero lettre montant;" if ($verbose);
    print "\n";
    print "numero lettre devis;" if ($verbose);
    print "\n";
    print "numero pointage;" if ($verbose);
    print "\n";
    print "numero rappel;" if ($verbose);
    print "0\n";
    print "type niveau;" if ($verbose);
    print "0\n";
    print "type revision;" if ($verbose);
    print "0\n";
    print "montant devise;" if ($verbose);
    print "\n";
    print "code taxe;" if ($verbose);
    if ($field[9] eq 'CREDIT') {
		print $field[22];
	}else{
	    print "\n";
	}
    print "norme;" if ($verbose);
    print "0\n";
    print "provenance;" if ($verbose);
    print "0\n";
    print "type penalites;" if ($verbose);
    print "0\n";
    print "date relance;" if ($verbose);
    print "\n";
    print "date de rapprochement;" if ($verbose);
    print "\n";
    print "????;" if ($verbose);
	print "\n";
    print "reference;" if ($verbose);
    print $field[12]."\n";
    print "status reglement;" if ($verbose);
    print "0\n";
    print "montant refle;" if ($verbose);
    print "0,00\n";
    print "date dernier reglement;" if ($verbose);
    print "\n";
    print "date operation;" if ($verbose);
    print "\n";
	print "?;" if ($verbose);
	print "0\n";
    print "?;" if ($verbose);
	print "\n";
	if ($field[7]) {
	    print "Ecriture Analytique;" if ($verbose);
	    print "#MECA\n";
	    print "numero de plan;" if ($verbose);
	    print "1\n";
	    print "section analytique;" if ($verbose);
	    print $field[7]."\n";
	    print "montant;" if ($verbose);
	    print $field[10]."\n";
	    print "quantite;" if ($verbose);
	    print "0,00\n";
	}
	if ($field[4] !~ /TVA/) {
		print "MIVA;" if ($verbose);
	    print "#MIVA\n";
		print "empty;" if ($verbose);
	    print "\n";
		print "tiers;" if ($verbose);
		print encode_utf8(substr(decode_utf8($field[15]), 0, 60))."\n";
	}
}
