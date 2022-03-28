#!/usr/bin/perl

use Encode;
use Data::Dumper;

$verbose = shift;

my %totalize;

while(<STDIN>) {
	chomp;
	my @field = split/;/ ;
	next if ($field[11] !~ /^FACTURE-/);
	if ($field[14] =~ /DEBIT/) {
			if (not defined $totalize{$field[16]}) {
				@{$totalize{$field[16]}} = @field;
			} else {
				$field[5] =~ s/,/\./;
				$totalize{$field[16]}[5] =~ s/,/\./;
				$totalize{$field[16]}[5] += $field[5];
			}
      next;
	}
	print "#MECG\n";
	print "code journal;" if ($verbose);
	print $field[15]."\n";
	print "date facture;" if ($verbose);
	$field[13] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[13]."\n";
	print "date paiement;" if ($verbose);
	$field[4] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
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
foreach my $k (keys(%totalize)) {
	@value = @{ $totalize{$k} };
	print "#MECG\n";
	print "code journal;" if ($verbose);
	print $value[15]."\n";
	print "date facture;" if ($verbose);
	$value[4] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $value[4]."\n";
	print "date paiement;" if ($verbose);
	print $value[4]."\n";
	print "piece;" if ($verbose);
	print $value[15].$value[16]."\n";
	print "numero de remise;" if ($verbose);
	print $value[16]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "numero compte;" if ($verbose);
	print $value[17]."\n";
	print "vide;" if ($verbose);
	print "\n";
	print "code comptable;" if ($verbose);
	print "\n";
	print "vide;" if ($verbose);
	print "\n";
	print "libelle;" if ($verbose);
	print "Remise ".lc($value[6]). " ".$value[16]."\n";
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
	$value[5] =~ s/\./,/;
	print $value[5]."\n";
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
