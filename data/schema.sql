create table if not exists tweets (
    id integer not null primary key,
    tweet varchar(255) not null,
    created datetime not null
);
create table if not exists tweet_logs (
    tweet_id integer not null,
    tweeted datetime not null
);
