<?php
require '_common.php';

use Abraham\TwitterOAuth\TwitterOAuth;

print date('Y-m-d H:i:s ');

try {
    $dsn = 'sqlite:' . ROOT . DS . 'data/apple_dokuzetsu.sqlite';
    $dbh = new PDO($dsn, null, null, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

$to = new TwitterOAuth(
    getenv('CONSUMER_KEY'),
    getenv('CONSUMER_SECRET'),
    getenv('ACCESS_TOKEN'),
    getenv('ACCESS_TOKEN_SECRET')
);

$stmt = $dbh->query('select id, tweet from tweets order by random() limit 1');

$tweets = $stmt->fetchAll();
if (empty($tweets)) {
    echo 'Tweets are empty.';
    exit;
}
$tweet = $tweets[0];

$req = $to->post('statuses/update', ['status' => $tweet['tweet']]);

$sql = 'insert into tweet_logs (tweet_id, tweeted) values (:id, :tweeted)';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':id', (int)$tweet['id'], PDO::PARAM_INT);
$stmt->bindValue(':tweeted', date('Y-m-d H:i:s'));
$stat = $stmt->execute();
