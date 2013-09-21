#! /usr/bin/perl

# Generate data from restapi table.

use strict;
use warnings;
use Data::Dumper;
use constant RANGE => 1000;
use constant USERS => 100; # start from 1
use constant STATS => 9; # start from 0


our $asql = [];


# 1. process stat_name
my $stat_file = "./stat_name.txt";
my $astats = [];
-f $stat_file or die("Stat Name File doesn't exist."); 
open(FH, $stat_file);
while (<FH>) {
  chomp;
  push(@$astats, $_);
}
close(FH);
# print Dumper($astats);

# 2. process username:stat_name:stat_value
my ($i, $j, $n);
foreach $i(1 .. USERS) {
  $i<10 and $i = '0'.$i;

  foreach $j(0 .. STATS) {
    $n = int(rand(RANGE));
    push (@$asql, qq{('user$i', '$astats->[$j]', $n)});
    # push (@$asql, '(user'.$i.", '".$astats->[$j]."', ".$n.')');
    # push (@$asql, ['user'.$i, $astats->[$j], $n]);
  }

}

print join(', ', @{$asql});
