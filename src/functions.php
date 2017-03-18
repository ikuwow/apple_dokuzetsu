<?php

// 二分探索法
// なので、オーダーがO(n)からO(log2(n))になる
function binary_search($needle, $haystack)
{
    $right = count($haystack)-1;
    $left = 0;

    while ( $left  < $right ){
        $mid = (int)(($right + $left ) / 2);
        if ($haystack[$mid] < $needle){
            $left  = $mid + 1;
        } else if ($haystack[$mid] > $needle) {
            $right = $mid - 1;
        } else {
            return $mid;
        }
    }

    if ( $left  != $right ){
        return $mid;
    } else {
        if ($haystack[$left] >= $needle) {
            return $left;
        } else {
            return $left+1;
        }
    }
}

// $lookup：$weights を加算していった配列
// たとえば$weights={5,2,8,10}だったら、$lookup={5,7,15,25}となる
// こうすることで、weghts 配列をソートすることなく、昇順の配列が得られる
function calc_lookups($weights){
    $lookup = array();
    $total_weight = 0;

    for ($i=0 ; $i < count($weights) ; $i++){
        $total_weight += $weights[$i];
        $lookup[$i] = $total_weight;
    }
    return array($lookup, $total_weight);
}

function weighted_random($weights, $lookup = null, $total_weight = null) {
  if ( $lookup == null ) {
    list($lookup, $total_weight) = calc_lookups($weights);
  }
  $rand = mt_rand(1,$total_weight);
  return binary_search($rand, $lookup);
}
