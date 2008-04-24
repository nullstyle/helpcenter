#!/usr/bin/perl

use Getopt::Long;

GetOptions('a!' => \$averages,
           'x!' => \$indiv,
          );

my ($cat, $url, $timer);
while (<>) {
  my ($t, $elapsed);
  ($cat, $url, $elapsed) = /Cache (hit|miss|refresh) at (\S*).*Request timer: ([^s]*)s/;

  if ($url) {
    print "$url $cat $elapsed\n" if ($indiv);
    $time{$url} += $elapsed;
    $count{$url}++;
    $max{$url} = $elapsed if ($elapsed > $max{$url});
    $min{$url} = $elapsed if (($elapsed < $max{$url}) || !defined($min{$url}));
  }
}

if ($averages) {
  print "\nAverage time for each URL:\n" if ($indiv);

  print "URL Average Count Max Min\n";
  foreach (keys %time) {
    print $_, " ", $time{$_} / $count{$_}, " ", $count{$_}, " ", $max{$_}, " ", $min{$_}, "\n";
  }
}