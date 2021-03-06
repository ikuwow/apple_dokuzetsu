<?php
require '_common.php';

use Abraham\TwitterOAuth\TwitterOAuth;

print date('Y-m-d H:i:s ');

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$followers = $to->get('followers/ids');
$friends = $to->get('friends/ids');

$counter = 0;
foreach ($followers->ids as $i => $id) {
    if (empty($friends->ids) or !in_array($id, $friends->ids)) {
        $req = $to->post('friendships/create', ['user_id' => $id]);
        if ($req) {
            $counter = $counter +1;
        }
    }
    if ($counter == 30) {
        break;
    }
}

print "Auto followed $counter user(s)." . PHP_EOL;
$result = json_decode($req);
var_dump($result);
