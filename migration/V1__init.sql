CREATE TABLE Address
(
    zip_code     VARCHAR(9),
    neighborhood VARCHAR(255),
    city         VARCHAR(255),
    state        VARCHAR(2)
);

CREATE TABLE Category
(
    id          INT PRIMARY KEY,
    name        VARCHAR(255),
    description TEXT
);

CREATE TABLE IF NOT EXISTS User
(
    id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    cpf      VARCHAR(11)  NULL,
    email    VARCHAR(255) NOT NULL,
    phone    VARCHAR(15)  NULL,
    password VARCHAR(512) NOT NULL
);

CREATE TABLE Product
(
    id           INT PRIMARY KEY,
    title        VARCHAR(255),
    description  TEXT,
    price        DECIMAL(10, 2),
    zip_code     VARCHAR(9),
    neighborhood VARCHAR(255),
    city         VARCHAR(255),
    state        VARCHAR(2),
    category_id  INT,
    user_id      INT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Category (id),
    FOREIGN KEY (user_id) REFERENCES User (id)
);

CREATE TABLE Interest
(
    id         INT PRIMARY KEY,
    message    TEXT,
    date_time  DATETIME,
    contact    VARCHAR(255),
    product_id INT,
    FOREIGN KEY (product_id) REFERENCES Product (id)
);

CREATE TABLE Product_photo
(
    product_id INT,
    photo_uri  VARCHAR(512),
    FOREIGN KEY (product_id) REFERENCES Product (id)
);