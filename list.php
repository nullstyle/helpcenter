<?php
# $Id$

## General functions for manipulating lists

# take: return a list of the first $n elements from $list
function take($n, $list) {
  if (!is_array($list)) throw new Exception("Non-array passed to 'take'");
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if (++$i > $n) { break; }
      array_push($result, $item);
  }
  return $result;
}

# take_range: return a list of elements from $list numbered $lo through $hi-1
function take_range($lo, $hi, $list) {
  $result = array();
  $i = 0;
  foreach ($list as $item) {
    if ($i >= $hi) { break; }
    if ($i >= $lo) {
      array_push($result, $item);
    }
    $i++;
  }
  return $result;
}

# singleton($array) is true if there is no more than one non-null item in $array
function singleton($array) {
  return count(array_filter($array)) <= 1;
}

# uniq($array) is a copy of $array with consecutive duplicates removed.
function uniq($array) {
  $deck = null;
  $result = array();
  foreach ($array as $item) {
    if ($item != $deck)
      array_push($result, $item);
    $deck = $item;
  }
  return $result;
}

# member($x, $list) indicates whether the item $x is a member of $list.
# PHP's array_search is useless, as it may return 0 when the element
# is first in the list, and 0 is indistinguishable from false.
function member($x, $list) {
  foreach ($list as $y) if ($x==$y) return true;
  return false;
}

# Superimposes all the key-value pairs from $a onto $b.
function superimpose($a, $b) {
  foreach ($a as $k => $v) {
    $b[$k] = $v;
  }
  return $b;
}

?>