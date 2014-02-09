<?php



// 二分探索法
// なので、オーダーがO(n)からO(log2(n))になる
function binary_search($needle, $haystack)
{
 $right = count($haystack)-1;
 $left = 0;

 // 左を示すポインタ($left)が右を示すポインタ($high)と同じ値か、
 // 大きい値となったとき、配列に対する$needleの相対位置が特定される
 while ( $left  < $right ){
  // (int)をつけることで$midを整数値にする
  $mid = (int)(($right + $left ) / 2);
  if ($haystack[$mid] < $needle){
   // 右半分へ
   $left  = $mid + 1;
  } else if ($haystack[$mid] > $needle) {
   // 左半分へ
   $right = $mid - 1;
  } else {
   // ちょうどぴったり
   return $mid;
  }
 // $haystackの中に$needleがない場合は、
 // この時点で$left > $rightになるのでループから抜ける
 }

 // ループ抜けたあとの判定
 if ( $left  != $right ){
  // $low が$highを追い越した場合は$midの値が相対位置
  return $mid;
 } else {
  // $needle は $haystack[$low] の左に来るか右に来るか
  if ($haystack[$left] >= $needle) {
   return $left ;
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
