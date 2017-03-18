<?php

print date('Y-m-d H:i:s ');

require_once("config.php");

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

// OAuthオブジェクト生成
$to = new TwitterOAuth(
    CONSUMER_KEY,
    CONSUMER_SECRET,
    ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
);

// フォローしているアカウントのIDを読み込み
$friends_ids = $to->get('friends/ids');
// $friends_list= $to->get('friends/list');

// フォロワーの情報を読込み
$followers_list = $to->get('followers/list');

// カウンタの初期化
$cnt_f = 0;
$cnt_r = 0;

$pokes = array(
	'フォローしてくれてありがとうお兄ちゃん！ スティーブ・ジョブズみたいな人間を目指してこれからも頑張ってね！',
	'フォローしてくれてありがとうお兄ちゃん！ 今度Appleについてオールナイトで語り合いましょうね！'
);

// フォロワーごとに回す
foreach ( $followers_list->users as $itr => $usr) {
	$cond = ( empty($friends_ids->ids) || !in_array($usr->id, $friends_ids->ids) )//
		&& !($usr->protected); 
	if ($cond) {
 		$req_f = $to->post('friendships/create', ['user_id' => $usr->id]);
 		if ($req_f) {
 			$cnt_f += 1;
			// リプライ
 			$reply = '@'.$usr->screen_name.' '.$pokes[mt_rand(0,count($pokes)-1)];
 		 	$req_r = $to->post('statuses/update',['status'=>$reply]);
		 	if ($req_r) $cnt_r += 1;
            }
          if ($cnt_f == 30) break;
	}
}

print "Auto followed $cnt_f user(s). \n";
print "$cnt_r poke message(s) were send. \n";

?>
