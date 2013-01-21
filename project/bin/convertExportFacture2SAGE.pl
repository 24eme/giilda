#!/usr/bin/perl

$verbose = shift;

while(<STDIN>) {
	chomp;
	@field = split/;/ ;
	print "Ecriture générale;" if ($verbose);
	print "#MECG\n";
	print "code journal;" if ($verbose);
        print $field[0]."\n";
	print "date;" if ($verbose);
	print $field[1]."\n";
        print "date saisie;" if ($verbose);
        print $field[2]."\n";
        print "numero de facture;" if ($verbose);
        print $field[3]."\n";
        print "piece;" if ($verbose);
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
        print $field[4]."\n";
        print "numero reglement;" if ($verbose);
        print "\n";
        print "echeance;" if ($verbose);
        print $field[8]."\n";
        print "partie;" if ($verbose);
        print "\n";
        print "quantite;" if ($verbose);
        print "\n";
        print "numero devis;" if ($verbose);
        print "\n";
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
        print "\n";
        print "type niveau;" if ($verbose);
        print "0\n";
        print "type revision;" if ($verbose);
        print "0\n";
        print "montant devise;" if ($verbose);
        print "\n";
        print "code taxe;" if ($verbose);
        print "\n";
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
        print "reference;" if ($verbose);
        print $field[12]."\n";
        print "status reglement;" if ($verbose);
        print "\n";
        print "montant refle;" if ($verbose);
        print "\n";
        print "date dernier reglement;" if ($verbose);
        print "\n";
        print "date operation;" if ($verbose);
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
	        print "0.00\n";
	}
}
