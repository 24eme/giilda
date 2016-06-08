#!/usr/bin/perl

use Encode;

$verbose = shift;

while(<STDIN>) {
	chomp;
	@field = split/;/ ;
        next if ($field[0] ne 'VEN' && $field[0] !~ /^[02]+$/);

	next if (!$field[10]); #si montant à 0, l'ignorer
        $field[10] = sprintf("%.2f", $field[10]);
	$field[10] =~ s/\./,/;
	print "Ecriture générale;" if ($verbose);
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
        $piece = $field[3]; $piece =~ s/[^a-z0-9]//ig;
        print $piece."\n";
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
		print "C03\n";
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
	print "\n"; # ??????????
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
		print $field[15]."\n";
	}
}
