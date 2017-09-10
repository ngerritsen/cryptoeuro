CREATE TABLE cryptocurrency (
    currency varchar(8) NOT NULL PRIMARY KEY,
    logo_url varchar(255),
    name varchar(64)
);

CREATE TABLE cryptocurrency_price (
    currency varchar(6) NOT NULL,
    time timestamp  NOT NULL,
    sell float NOT NULL,
    buy float NOT NULL,
    PRIMARY KEY (currency, time),
    FOREIGN KEY (currency) REFERENCES cryptocurrency(currency)
);

CREATE TABLE bitcoin_price (
    time timestamp  NOT NULL PRIMARY KEY,
    sell float NOT NULL,
    buy float NOT NULL
);
