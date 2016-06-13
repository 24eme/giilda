#!/usr/bin/perl

use Encode;

$productfile = shift;
my %comptachapeau;
if (open(FH, $productfile)) {
   while (<FH>) {
       @prod = split(/;/);
       if ($prod[6]) {
           $comptachapeau{$prod[4]}{'master_compta'} = $prod[6];
           $comptachapeau{$prod[4]}{'master_cvo'} = $prod[7];
           $comptachapeau{$prod[4]}{'global_cvo'} = $prod[5];
       }
   }
   close FH;
}

while(<STDIN>) {
        chomp;
        @field = split/;/ ;
        if ( $comptachapeau{$field[5]} ) {
           $montant = $field[10];
           $code = $field[5];
           $field[10] = sprintf('%.02f', $montant * (1 - $comptachapeau{$code}{'master_cvo'} / $comptachapeau{$code}{'global_cvo'}));
           print join(';', @field)."\n";
           $field[10] = $montant - $field[10];
           $field[5] = $comptachapeau{$field[5]}{'master_compta'};
           print join(';', @field)."\n";
        } else {
           print join(';', @field)."\n";
        }
}

