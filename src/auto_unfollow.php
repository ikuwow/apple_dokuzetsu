<?php
require '_common.php';

use Abraham\TwitterOAuth\TwitterOAuth;

print date("Y-m-d H:i:s") . " Unfollowing start.\n";

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$followers = $to->get('followers/ids', array('cursor' => -1));
$friends = $to->get('friends/ids', array('cursor' => -1));

foreach ($friends->ids as $i => $id) {
    if (!in_array($id, $followers->ids)) {
        $req = $to->post('friendships/destroy', array('user_id' => $id));
        if ($req) {
            print "Unfollowed $id.\n";
        } else {
            print "Failed to unfollow $id.\n";
        }
    }
}

print "Unfollowing end. \n";
