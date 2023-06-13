create table Address
(
    zip_code     varchar(9)   null,
    neighborhood varchar(255) null,
    city         varchar(255) null,
    state        varchar(2)   null
);

create table Category
(
    id          int auto_increment not null primary key,
    name        varchar(255)       null,
    description text               null
);

create table User
(
    id       int auto_increment not null primary key,
    name     varchar(255)       not null,
    cpf      varchar(11)        not null,
    email    varchar(255)       null,
    phone    varchar(15)        not null,
    password varchar(512)       not null
);

create table Product
(
    id           int auto_increment                  not null primary key,
    title        varchar(255)                        null,
    description  text                                null,
    price        int                                 null,
    zip_code     varchar(9)                          null,
    neighborhood varchar(255)                        null,
    city         varchar(255)                        null,
    state        varchar(2)                          null,
    category_id  int                                 null,
    user_id      int                                 null,
    created_at   timestamp default CURRENT_TIMESTAMP not null,
    constraint Product_ibfk_1
        foreign key (category_id) references Category (id),
    constraint Product_ibfk_2
        foreign key (user_id) references User (id)
);

create table Interest
(
    id         int auto_increment                  not null primary key,
    message    text                                null,
    date_time  timestamp default CURRENT_TIMESTAMP not null,
    contact    varchar(255)                        null,
    product_id int                                 null,
    constraint Interest_ibfk_1
        foreign key (product_id) references Product (id)
);

create index product_id
    on Interest (product_id);

create index category_id
    on Product (category_id);

create index user_id
    on Product (user_id);

create table Product_photo
(
    product_id int          null,
    photo_uri  varchar(512) null,
    constraint Product_photo_ibfk_1
        foreign key (product_id) references Product (id)
);

create index product_id
    on Product_photo (product_id);

INSERT INTO Category (name, description)
VALUES ('Eletrônicos', 'Celulares, computadores, tablets, etc.'),
       ('Móveis', 'Mesas, cadeiras, sofás, etc.'),
       ('Eletrodomésticos', 'Geladeiras, fogões, microondas, etc.'),
       ('Roupas', 'Camisetas, calças, vestidos, etc.'),
       ('Calçados', 'Tênis, sapatos, chinelos, etc.'),
       ('Acessórios', 'Bolsas, relógios, óculos, etc.'),
       ('Livros', 'Romances, biografias, etc.'),
       ('Jogos', 'Videogames, jogos de tabuleiro, etc.'),
       ('Brinquedos', 'Bonecas, carrinhos, etc.'),
       ('Outros', 'Outros produtos');

INSERT INTO Address (zip_code, neighborhood, city, state)
VALUES ('12345678', 'Centro', 'São Paulo', 'SP'),
       ('87654321', 'Centro', 'Rio de Janeiro', 'RJ'),
       ('45678901', 'Centro', 'Belo Horizonte', 'MG'),
       ('98765432', 'Centro', 'Curitiba', 'PR'),
       ('23456789', 'Centro', 'Porto Alegre', 'RS'),
       ('56789012', 'Centro', 'Salvador', 'BA'),
       ('10987654', 'Centro', 'Fortaleza', 'CE'),
       ('21098765', 'Centro', 'Brasília', 'DF'),
       ('32109876', 'Centro', 'Goiânia', 'GO'),
       ('43210987', 'Centro', 'Manaus', 'AM');