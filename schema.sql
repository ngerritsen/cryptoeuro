CREATE TABLE cryptocurrencies (
    tag varchar(8) NOT NULL PRIMARY KEY,
    transaction_cost float,
    logo_url varchar(255),
    name varchar(16)
);

CREATE TABLE cryptocurrency_price (
    tag varchar(6) NOT NULL,
    time timestamp  NOT NULL,
    sell float NOT NULL,
    buy float NOT NULL,
    PRIMARY KEY (tag, time)
);

CREATE TABLE bitcoin_price (
    tag varchar(6) NOT NULL,
    time timestamp  NOT NULL,
    sell float NOT NULL,
    buy float NOT NULL,
    PRIMARY KEY (tag, time)
);
