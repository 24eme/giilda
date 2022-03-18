#!/usr/bin/perl

use Encode;

$verbose = shift;

while(<STDIN>) {
	chomp;
	my @field = split/;/ ;
	next if ($field[11] !~ /^FACTURE-/);
	print "#MECG\n";
	print "code journal;" if ($verbose);
	print $field[13]."\n";
	print "date facture;" if ($verbose);
	print substr($field[11], -4, 2).substr($field[11], -6, 2).substr($field[11], -8, 2)."\n";
	print "date paiement;" if ($verbose);
	$field[4] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[4]."\n";
	print "piece;" if ($verbose);
	print $field[13].$field[14]."\n";
	print "numero de remise;" if ($verbose);
	print $field[14]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "numero compte;" if ($verbose);
	print $field[15]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "code comptable;" if ($verbose);
	print $field[2]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "libelle;" if ($verbose);
	print "Remise ".lc($field[6]). " ".$field[14]."\n";
	print "?;" if ($verbose);
	print "1\n";
	print "vide;" if ($verbose);
	print "\n";
	print "vide;" if ($verbose);
	print "\n";
	print "?;" if ($verbose);
	print "0\n";
	print "?;" if ($verbose);
	print "0\n";
	print "sens (credit = 1 / debit = 0);" if ($verbose);
	print "1\n";
	print "montant;" if ($verbose);
	print $field[5]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "vide;" if ($verbose);
	print "\n";
	print "vide;" if ($verbose);
	print "\n";
	print "?;" if ($verbose);
	print "0\n";
	print "?;" if ($verbose);
	print "0\n";
	print "?;" if ($verbose);
	print "0\n";
	print "?;" if ($verbose);
	print "0\n";
	print "vide;" if ($verbose);
	print "\n";
}
