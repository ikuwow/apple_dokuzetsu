create table if not exists tweets (
    id rowid,
    tweet varchar(255) not null,
    created integer not null default now
);
create table if not exists tweet_logs (
    id rowid,
    tweet_id int not null,
    created integer not null default now
);
