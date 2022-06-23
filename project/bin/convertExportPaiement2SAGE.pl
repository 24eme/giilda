#!/usr/bin/perl

use Encode;
use Data::Dumper;
use warnings;

#$verbose = shift;

my %byRemise;

while(<STDIN>) {
	chomp;
	my @field = split/;/ ;
	next if ($field[11] !~ /^FACTURE-/);
	push @{$byRemise{$field[16]}}, \@field;
}
foreach my $i (keys(%byRemise)) {
  my @totalize = ();
	my @value = @{$byRemise{$i}};
	foreach my $j (@value) {
		my @field = @{$j};
		if ($field[14] =~ /DEBIT/) {
			if (scalar @totalize == 0) {
					@totalize = @field;
				} else {
					$field[5] =~ s/,/\./;
					$totalize[5] =~ s/,/\./;
					$totalize[5] += $field[5];
				}
	      next;
		}
		print "#MECG\n";
		print "code journal;" if ($verbose);
		print $field[15]."\n";
		print "date paiement;" if ($verbose);
		$field[4] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
		print $field[4]."\n";
		print "date paiement;" if ($verbose);
		print $field[4]."\n";
		print "piece;" if ($verbose);
		print $field[15].$field[16]."\n";
		print "numero de remise;" if ($verbose);
		print $field[16]."\n";
		print "vide;" if ($verbose);
		print "\n";
		print "numero compte;" if ($verbose);
		print $field[17]."\n";
		print "vide;" if ($verbose);
		print "\n";
		print "code comptable;" if ($verbose);
		print $field[2]."\n";
		print "vide;" if ($verbose);
		print "\n";
		print "libelle;" if ($verbose);
		print "Remise ".lc($field[6]). " ".$field[16]."\n";
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
	print "#MECG\n";
	print "code journal;" if ($verbose);
	print $totalize[15]."\n";
	print "date facture;" if ($verbose);
	$totalize[4] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $totalize[4]."\n";
	print "date paiement;" if ($verbose);
	print $totalize[4]."\n";
	print "piece;" if ($verbose);
	print $totalize[15].$totalize[16]."\n";
	print "numero de remise;" if ($verbose);
	print $totalize[16]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "numero compte;" if ($verbose);
	print $totalize[17]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "code comptable;" if ($verbose);
	print "\n";
	print "vide;" if ($verbose);
	print "\n";
	print "libelle;" if ($verbose);
	print "Remise ".lc($totalize[6]). " ".$totalize[16]."\n";
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
	print "0\n";
	print "montant;" if ($verbose);
	$totalize[5] =~ s/\./,/;
	print $totalize[5]."\n";
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
