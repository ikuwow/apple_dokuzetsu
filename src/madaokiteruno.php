<?php
require '_common.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$tweet = 'お兄ちゃん、まだ起きてるの？ 今日はKeynoteはなかったと思うけど。';
$req = $to->post('statuses/update',array('status'=>$tweet));

?>
