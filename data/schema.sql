create table if not exists tweets (
    id integer not null primary key,
    tweet varchar(255) not null,
    created integer not null default now
);
create table if not exists tweet_logs (
    tweet_id int not null,
    tweeted integer not null default now
);
