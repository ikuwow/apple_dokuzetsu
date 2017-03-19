<?php
require '_common.php';

use Abraham\TwitterOAuth\TwitterOAuth;

print date("Y-m-d H:i:s") . " Unfollowing start." . PHP_EOL;

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$followers = $to->get('followers/ids', ['cursor' => -1]);
$friends = $to->get('friends/ids', ['cursor' => -1]);

foreach ($friends->ids as $i => $id) {
    if (!in_array($id, $followers->ids)) {
        $req = $to->post('friendships/destroy', ['user_id' => $id]);
        if ($req) {
            print "Unfollowed {$id}." . PHP_EOL;
        } else {
            print "Failed to unfollow {$id}." . PHP_EOL;
        }
    }
}

print "Unfollowing end." . PHP_EOL;
