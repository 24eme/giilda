#!/usr/bin/perl

use Encode;
use Env;

use constant code_journal => 0;
use constant date => 1;
use constant date_de_saisie => 2;
use constant numero_de_facture => 3;
use constant libelle => 4;
use constant compte_general => 5;
use constant compte_tiers => 6;
use constant compte_analytique => 7;
use constant date_echeance => 8;
use constant sens => 9;
use constant montant => 10;
use constant piece => 11;
use constant reference => 12;
use constant id_couchdb => 13;
use constant type_ligne => 14;
use constant nom_client => 15;
use constant code_comptable_client => 16;
use constant origine_type => 17;
use constant produit_type => 18;
use constant origine_id => 19;
use constant volume => 20;
use constant cvo => 21;
use constant code_tva => 22;
use constant numero_piece_comptable => 23;
use constant numero_adherent => 24;
use constant numero_reglement => 25;


$verbose = shift;
my $htTvaAGG = $ENV{EXPORT_SAGE_FACTURE_HT_TVA_AGG};

my $totalHt = 0;
my $totalTva = 0;
my @infosHt = ();
my @infosTva = ();
my $max_miva = 30;
my $max_intitule = 35;

if ($ENV{EXPORT_SAGE_MAX_MIVA}) {
    $max_miva = $ENV{EXPORT_SAGE_MAX_MIVA};
}
if ($ENV{EXPORT_SAGE_MAX_INTITULE}) {
    $max_intitule = $ENV{EXPORT_SAGE_MAX_INTITULE};
}

while(<STDIN>) {
	chomp;
	my @field = split/;/ ;
    next if ($field[code_journal] ne 'VEN' && $field[code_journal] !~ /^[02]+$/);
	next if ($field[montant] == 0  && $field[type_ligne] !~ /TVA/);
	if ($field[type_ligne] =~ /TVA/) {
	    $totalTva += $field[montant];
	    @infosTva = @field;
        next if ($htTvaAGG == 1);
	}
	if ($field[type_ligne] =~ /LIGNE/) {
	    $totalHt += $field[montant];
	    @infosHt = @field;
        next if ($htTvaAGG == 1);
	}
	print "Ecriture générale;" if ($verbose);
	printSageEntryFunction(\@field);
    if ($field[type_ligne] =~ /TVA/) {
		$montant_tva_isset = 1;
	    $montant_tva = $field[montant];
	    $compte_tva = $field[compte_general];
	}
	if ($field[sens] =~ /DEBIT/) {
	    $montant_ttc = $field[montant];
	    $date = $field[date_de_saisie];
	    $date_echeance = $field[date_echeance];
	    $code_client = $field[compte_tiers];
	}
	if ($montant_tva_isset && $montant_ttc) {
	    print "MRGT;" if ($verbose);
	    print "#MRGT\n";
	    print "\n";
	    print "1\n";
	    print "date;" if ($verbose);
        $date_echeance =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	    print $date_echeance."\n";
	    print "date saisie;" if ($verbose);
        $date =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
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
	    print $field[code_tva]."\n";
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
    my $montant = $field[montant];
    $field[montant] = sprintf("%.2f", $field[montant]);
    $field[montant] =~ s/\./,/;
    print "#MECG\n";
	print "code journal;" if ($verbose);
    print $field[code_journal]."\n";
	print "date;" if ($verbose);
	$field[date] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[date]."\n";
    print "date saisie;" if ($verbose);
	$field[date_de_saisie] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
	print $field[date_de_saisie]."\n";
    print "piece;" if ($verbose);
	print $field[numero_piece_comptable]."\n";
    print "numero de facture;" if ($verbose);
    print $field[numero_de_facture]."\n";
    print "piece tréso;" if ($verbose);
    print $field[piece]."\n";
    print "numero compte general;" if ($verbose);
    print $field[compte_general]."\n";
    print "numero compte general contre partie;" if ($verbose);
    print "\n";
    print "numero compte tiers;" if ($verbose);
    print $field[compte_tiers]."\n";
    print "numero compte tiers contre partie;" if ($verbose);
    print "\n";
    print "intitule;" if ($verbose);
    print encode_utf8(substr(decode_utf8($field[libelle]), 0, $max_intitule))."\n";
    print "numero reglement;" if ($verbose);
	print $field[numero_reglement]."\n";
    print "date echeance;" if ($verbose);
	$field[date_echeance] =~ s/\d{2}(\d{2})-(\d{2})-(\d{2})/${3}${2}${1}/;
    print $field[date_echeance]."\n";
    print "partie;" if ($verbose);
    print "\n";
    print "quantite;" if ($verbose);
    print "\n";
    print "numero devis;" if ($verbose);
    print "0\n";
    print "sens (credit = 1 / debit = 0);" if ($verbose);
    if ($field[sens] eq 'CREDIT') {
		print "1\n";
	} else {
		print "0\n";
	}
    print "montant;" if ($verbose);
    print $field[montant]."\n";
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
    if ($field[sens] eq 'CREDIT') {
		print $field[code_tva]."\n";
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
    print $field[reference]."\n";
    print "status reglement;" if ($verbose);
    print "0\n";
    print "montant refle;" if ($verbose);
    print "\n";
    print "date dernier reglement;" if ($verbose);
    print "\n";
    print "date operation;" if ($verbose);
    print "\n";
	print "?;" if ($verbose);
	print "0\n";
    print "?;" if ($verbose);
	print "\n";
	if ($field[compte_analytique]) {
	    print "Ecriture Analytique;" if ($verbose);
	    print "#MECA\n";
	    print "numero de plan;" if ($verbose);
	    print "1\n";
	    print "section analytique;" if ($verbose);
	    print $field[compte_analytique]."\n";
	    print "montant;" if ($verbose);
	    print $field[montant]."\n";
	    print "quantite;" if ($verbose);
	    print "0,00\n";
	}
	if ($field[libelle] !~ /TVA/) {
		print "MIVA;" if ($verbose);
	    print "#MIVA\n";
		print "empty;" if ($verbose);
	    print "\n";
		print "tiers;" if ($verbose);
		print encode_utf8(substr(decode_utf8($field[nom_client]), 0, $max_miva))."\n";
	}
}
