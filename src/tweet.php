<?php
require '_common.php';
require '_functions.php';

use Abraham\TwitterOAuth\TwitterOAuth;

print date('Y-m-d H:i:s ');

try {
    $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
    $dbh = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    var_dump($e->getMessage());
    exit;
}

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$stmt = $dbh->query('SELECT * FROM tweets ORDER BY ID');

$tweets = $stmt->fetchAll();
$nums = array();
foreach ($tweets as $tweet) {
    $nums[] = $tweet['num_of_times'];
}
$max_weight = max($nums);

$weights = array();
foreach ($nums as $key => $num) {
    $weights[$key] = $max_weight-$num+1;
}

$tw_idx = weighted_random($weights);
$tweet_id = $tw_idx+1;

$req = $to->post('statuses/update', array('status'=>$tweets[$tw_idx]['tweet']));

$sql = 'UPDATE tweets set num_of_times = :num where ID = :id';
$stmt = $dbh->prepare($sql);
$stat = $stmt->execute(array(':num'=>$tweets[$tw_idx]['num_of_times']+1,':id'=>$tweet_id));

if ($stat) {
    $tmp = $tweets[$tw_idx]['num_of_times'] +1;
    print "Tweet $tweet_id was incremented to $tmp.\n";
} else {
    print "Cannot incremented.";
}
