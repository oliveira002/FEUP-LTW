PRAGMA foreign_keys = on;

/*******************************************************************************
   Create Tables
********************************************************************************/

DROP TABLE IF EXISTS User;

CREATE TABLE User(
    idUser      INTEGER NOT NULL PRIMARY KEY,
    email       VARCHAR(30) NOT NULL UNIQUE,
    password    VARCHAR(30) NOT NULL,
    firstName   VARCHAR(30) NOT NULL,
    lastName    VARCHAR(30) NOT NULL,
    address     VARCHAR(70) NOT NULL,
    phoneNumber VARCHAR(13) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS Restaurant;

CREATE TABLE Restaurant(
    idRestaurant INTEGER NOT NULL PRIMARY KEY,
    name         VARCHAR(20) NOT NULL,
    phoneNumber  VARCHAR(13) NOT NULL UNIQUE,
    tax          FLOAT NOT NULL,
    minTime      INTEGER NOT NULL,
    address     VARCHAR(70) NOT NULL,
    maxTime      INTEGER NOT NULL,
    priceGroup   VARCHAR(20) NOT NULL
);

DROP TABLE IF EXISTS Category;

CREATE TABLE Category(
    idCategory INTEGER NOT NULL PRIMARY KEY,
    name       VARCHAR(20) NOT NULL
);

DROP TABLE IF EXISTS RestaurantCategory;

CREATE TABLE RestaurantCategory(
    idRestaurant INTEGER NOT NULL,
    idCategory   INTEGER NOT NULL,
    PRIMARY KEY(idCategory, idRestaurant),
    FOREIGN KEY (idCategory) REFERENCES Category ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS RestaurantOwner;

CREATE TABLE RestaurantOwner(
    idUser       INTEGER NOT NULL,
    idRestaurant INTEGER NOT NULL,
    PRIMARY KEY(idUser, idRestaurant),
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS UserOrder;

CREATE TABLE UserOrder(
    idOrder      INTEGER PRIMARY KEY AUTOINCREMENT,
    date         DATE NOT NULL,
    state        VARCHAR(20) NOT NULL,
    idUser       INTEGER NOT NULL,
    idRestaurant INTEGER NOT NULL,
    address     VARCHAR(70) NOT NULL,
    price FLOAT,
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS OrderProduct;

CREATE TABLE OrderProduct(
    idOrder   INTEGER NOT NULL,
    idProduct INTEGER NOT NULL,
    quantity  INTEGER NOT NULL,
    PRIMARY KEY (idOrder, idProduct)
    FOREIGN KEY (idOrder) REFERENCES UserOrder ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idProduct) REFERENCES Product ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS Menu;

CREATE TABLE Menu(
    idMenu       INTEGER PRIMARY KEY,
    name         VARCHAR(20) NOT NULL,
    idRestaurant INTEGER NOT NULL,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS Product;

CREATE TABLE Product(
    idProduct   INTEGER PRIMARY KEY,
    name        VARCHAR(20) NOT NULL,
    price       INTEGER NOT NULL
);

DROP TABLE IF EXISTS ProductMenu;

CREATE TABLE ProductMenu(
    idProduct INTEGER NOT NULL,
    idMenu    INTEGER NOT NULL,
    PRIMARY KEY (idProduct, idMenu)
    FOREIGN KEY (idMenu) REFERENCES Menu ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idProduct) REFERENCES Product ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS Review;

CREATE TABLE Review(
    idReview     INTEGER PRIMARY KEY,
    rating       INTEGER NOT NULL,
    comment      VARCHAR(300) NOT NULL,
    rDate        DATE NOT NULL,
    answer       VARCHAR(300),
    idUser       INTEGER NOT NULL,
    idRestaurant INTEGER NOT NULL,
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS FavoriteProduct;

CREATE TABLE FavoriteProduct(
    idUser      INTEGER NOT NULL,
    idProduct   INTEGER NOT NULL,
    PRIMARY KEY(idUser, idProduct),
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idProduct) REFERENCES Product ON UPDATE CASCADE ON DELETE CASCADE
);

DROP TABLE IF EXISTS FavoriteRestaurant;

CREATE TABLE FavoriteRestaurant(
    idUser      INTEGER NOT NULL,
    idRestaurant   INTEGER NOT NULL,
    PRIMARY KEY(idUser, idRestaurant),
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
);

/*******************************************************************************
   Populate Tables
********************************************************************************/

/**
    idUser      INTEGER NOT NULL PRIMARY KEY,
    email       VARCHAR(30) NOT NULL UNIQUE,
    password    VARCHAR(30) NOT NULL,
    firstName   VARCHAR(30) NOT NULL,
    lastName    VARCHAR(30) NOT NULL,
    address     VARCHAR(70) NOT NULL,
    phoneNumber VARCHAR(13) NOT NULL UNIQUE
*/

/* Password is 1234 */
INSERT INTO User VALUES(1, "email@gmail.com", "$2y$12$a0Qq1z5jz6ybIiA40BImtuVV.soR95/Aff41K5G8cqXTcU5hMUKxq", "User", "Name", "R. Nova da Junqueira 61, 4405-778 Madalena", "312415215");

/**
    idCategory INTEGER NOT NULL PRIMARY KEY,
    name       VARCHAR(20) NOT NULL
*/

INSERT INTO Category VALUES(1, "Fast Food");
INSERT INTO Category VALUES(2, "Chicken");
INSERT INTO Category VALUES(3, "Salad");
INSERT INTO Category VALUES(4, "Desserts");
INSERT INTO Category VALUES(5, "Sandwiches");
INSERT INTO Category VALUES(6, "Mexican");
INSERT INTO Category VALUES(7, "Healthy");
INSERT INTO Category VALUES(8, "Breakfast");
INSERT INTO Category VALUES(9, "Soup");
INSERT INTO Category VALUES(10, "Seafood");
INSERT INTO Category VALUES(11, "Vegan");
INSERT INTO Category VALUES(12, "Italian");

/**
    idRestaurant INTEGER NOT NULL PRIMARY KEY,
    name         VARCHAR(20) NOT NULL,
    phoneNumber  VARCHAR(13) NOT NULL UNIQUE,
    tax          FLOAT NOT NULL,
    minTime      INTEGER NOT NULL,
    address     VARCHAR(70) NOT NULL,
    maxTime      INTEGER NOT NULL
*/

INSERT INTO Restaurant VALUES(1, "McDonald's ® (Bom Sucesso)", "939939939", 0.40, 10, "Praça de Mouzinho de Albuquerque, 4150-365 Porto", 20, "low-cost");

/**
    idRestaurant INTEGER NOT NULL,
    idCategory   INTEGER NOT NULL,
    PRIMARY KEY(idCategory, idRestaurant),
    FOREIGN KEY (idCategory) REFERENCES Category ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
*/

INSERT INTO RestaurantCategory VALUES(1, 1);
INSERT INTO RestaurantCategory VALUES(1, 2);
INSERT INTO RestaurantCategory VALUES(1, 3);
INSERT INTO RestaurantCategory VALUES(1, 4);
INSERT INTO RestaurantCategory VALUES(1, 8);

/*
    idMenu       INTEGER PRIMARY KEY,
    name         VARCHAR(20) NOT NULL,
    idRestaurant INTEGER NOT NULL,
*/

INSERT INTO Menu VALUES(1, "Novidades", 1);
INSERT INTO Menu VALUES(2, "Ofertas Especiais", 1);
INSERT INTO Menu VALUES(3, "Sanduíches e McMenu", 1);
INSERT INTO Menu VALUES(4, "Happy Meal", 1);
INSERT INTO Menu VALUES(5, "Sobremesas", 1);
INSERT INTO Menu VALUES(6, "Saladas", 1);
INSERT INTO Menu VALUES(7, "Acompanhamentos e molhos", 1);
INSERT INTO Menu VALUES(8, "Bebidas", 1);

/**
    idReview     INTEGER PRIMARY KEY,
    rating       INTEGER NOT NULL,
    comment      VARCHAR(300) NOT NULL,
    rDate        DATE NOT NULL,
    idUser       INTEGER NOT NULL,
    idRestaurant INTEGER NOT NULL,
    FOREIGN KEY (idUser) REFERENCES User ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idRestaurant) REFERENCES Restaurant ON UPDATE CASCADE ON DELETE CASCADE
*/

INSERT INTO Review VALUES(1, 5, "Test visual with rating 5", "2022-05-10", NULL, 1, 1);
INSERT INTO Review VALUES(2, 4, "Test visual with rating 4", "2022-05-10", "Test restaurant answer", 1, 1);
INSERT INTO Review VALUES(3, 3, "Test visual with rating 3", "2022-05-10", NULL, 1, 1);
INSERT INTO Review VALUES(4, 2, "Test visual with rating 2", "2022-05-10", NULL, 1, 1);
INSERT INTO Review VALUES(5, 1, "Test visual with rating 1", "2022-05-10", NULL, 1, 1);
INSERT INTO Review VALUES(6, 0, "Test visual with rating 0", "2022-05-10", NULL, 1, 1);

/*
    idProduct   INTEGER PRIMARY KEY,
    name        VARCHAR(20) NOT NULL,
    price       INTEGER NOT NULL,
*/

INSERT INTO Product VALUES(1, "Kansas Steakhouse Double", 7.90);
INSERT INTO Product VALUES(2, "3 McMenus + ShareBox 10 Chicken Mcnuggets", 18.50);
INSERT INTO Product VALUES(3, "Happy Meal ® Chicken McNuggets ®", 4.50);
INSERT INTO Product VALUES(4, "McFlurry Snickers", 2.85);
INSERT INTO Product VALUES(5, "Salada Mista", 2.50);
INSERT INTO Product VALUES(6, "Batata Pequena", 2.00);
INSERT INTO Product VALUES(7, "Coca Cola Pequena", 2.50);
INSERT INTO Product VALUES(8, "2 McMenus + McMenu Grande + ShareBox 10 Chicken McNuggets", 19.00);
INSERT INTO Product VALUES(9, "McMenus + 2 McMenu Grande + ShareBox 10 Chicken McNuggets", 19.50);
INSERT INTO Product VALUES(10, "3 McMenu Grande + ShareBox 10 Chicken McNuggets", 20.00);
INSERT INTO Product VALUES(11, "Kansas Steakhouse Single", 6.50);
INSERT INTO Product VALUES(12, "Kansas Steakhouse Chicken", 6.50);
INSERT INTO Product VALUES(13, "Rustic Checken Chutney Manga", 7.50);
INSERT INTO Product VALUES(14, "Rustic Chicken Mostarda e Mel", 7.50);
INSERT INTO Product VALUES(16, "Big Tasty ® Double", 7.90);
INSERT INTO Product VALUES(17, "Big Tasty ® Single", 6.50);
INSERT INTO Product VALUES(18, "CBO ®", 7.00);
INSERT INTO Product VALUES(19, "McRoyal ® Bacon", 5.50);
INSERT INTO Product VALUES(20, "McRoyal ® Deluxe", 5.50);
INSERT INTO Product VALUES(21, "McRoyal ® Cheese", 5.50);
INSERT INTO Product VALUES(22, "McVeggie", 5.50);
INSERT INTO Product VALUES(23, "Big Mac ®", 4.70);
INSERT INTO Product VALUES(24, "Double Cheeseburger", 4.35);
INSERT INTO Product VALUES(25, "McChicken ®", 4.35);
INSERT INTO Product VALUES(26, "Filet-o-Fish ®", 4.35);
INSERT INTO Product VALUES(27, "Happy Meal ® Cheeseburger", 4.50);
INSERT INTO Product VALUES(28, "Happy Meal ® Hamburger", 4.50);
INSERT INTO Product VALUES(29, "Happy Meal ® McWeap Chicken Mayo", 4.50);
INSERT INTO Product VALUES(30, "Happy Meal ® Cheeseburguer Natura", 4.50);
INSERT INTO Product VALUES(31, "Happy Meal ® Hamburger Natura", 4.50);
INSERT INTO Product VALUES(32, "Happy Meal ® Douradinhos", 4.50);
INSERT INTO Product VALUES(33, "McFlurry ® M&M's ®", 2.00);
INSERT INTO Product VALUES(34, "McFlurry ® Oreo ®", 2.00);
INSERT INTO Product VALUES(35, "McFlurry ® KitKat ®", 2.00);
INSERT INTO Product VALUES(36, "Sundae Chocolate", 1.90);
INSERT INTO Product VALUES(37, "Sundae Caramelo", 1.90);
INSERT INTO Product VALUES(38, "Sundae Morango", 2.00);
INSERT INTO Product VALUES(39, "Sundae Baba de Camelo", 2.20);
INSERT INTO Product VALUES(40, "Abacaxi", 1.50);
INSERT INTO Product VALUES(41, "Fatias Maça", 1.50);
INSERT INTO Product VALUES(42, "Polpa Fruta", 1.50);
INSERT INTO Product VALUES(43, "Chicken McNuggets ® 4", 2.30);
INSERT INTO Product VALUES(44, "Chicken Delights", 2.30);
INSERT INTO Product VALUES(45, "ShareBox 10 McNuggets ®", 4.00);
INSERT INTO Product VALUES(46, "ShareBox 20 McNuggets ®", 7.75);
INSERT INTO Product VALUES(47, "Chicken Bacon", 2.30);
INSERT INTO Product VALUES(48, "Snack McWrap Chicken Mayo", 2.30);
INSERT INTO Product VALUES(49, "Snack McWrap Chicken Cheese", 2.30);
INSERT INTO Product VALUES(50, "Hamburguer", 1.50);
INSERT INTO Product VALUES(51, "Cheeseburger", 1.90);
INSERT INTO Product VALUES(52, "McDouradinhos", 2.50);
INSERT INTO Product VALUES(53, "Molho Batatas", 0.70);
INSERT INTO Product VALUES(54, "Molho Maionese e Alho", 0.70);
INSERT INTO Product VALUES(55, "Molho Caril", 0.70);
INSERT INTO Product VALUES(56, "Molho Agridoce", 0.70);
INSERT INTO Product VALUES(57, "Molho Barbecue", 0.70);
INSERT INTO Product VALUES(58, "Fanta Laranja Pequena", 2.30);
INSERT INTO Product VALUES(59, "IceTea Pessego Pequeno", 2.30);
INSERT INTO Product VALUES(60, "Lipton Chá Verde Hortelã e Lima", 2.40);
INSERT INTO Product VALUES(61, "Limonada Pequena", 2.30);
INSERT INTO Product VALUES(62, "Coca Zero Pequena", 2.30);
INSERT INTO Product VALUES(63, "Bongo Manga", 2.00);
INSERT INTO Product VALUES(64, "Um Bongo 8 Frutos", 2.00);
INSERT INTO Product VALUES(65, "Compal Laranja do Algarve", 2.10);
INSERT INTO Product VALUES(66, "Compal Manga/Laranja", 2.10);
INSERT INTO Product VALUES(67, "Compal Pêssego", 2.10);
INSERT INTO Product VALUES(68, "Água", 1.80);
INSERT INTO Product VALUES(69, "Água 0,33cl", 1.50);
INSERT INTO Product VALUES(70, "Luso Fruta Limão", 2.40);
INSERT INTO Product VALUES(71, "Luso Frutos Vermelhos 0,33cl", 2.30);

/*
    idProduct INTEGER NOT NULL PRIMARY KEY,
    idMenu    INTEGER NOT NULL,
*/

INSERT INTO ProductMenu VALUES(1, 1);
INSERT INTO ProductMenu VALUES(1, 3);
INSERT INTO ProductMenu VALUES(2, 2);
INSERT INTO ProductMenu VALUES(8, 2);
INSERT INTO ProductMenu VALUES(9, 2);
INSERT INTO ProductMenu VALUES(10, 2);
INSERT INTO ProductMenu VALUES(3, 4);
INSERT INTO ProductMenu VALUES(4, 5);
INSERT INTO ProductMenu VALUES(5, 6);
INSERT INTO ProductMenu VALUES(6, 7);
INSERT INTO ProductMenu VALUES(7, 8);
INSERT INTO ProductMenu VALUES(11, 1);
INSERT INTO ProductMenu VALUES(12, 1);
INSERT INTO ProductMenu VALUES(13, 1);
INSERT INTO ProductMenu VALUES(14, 1);
INSERT INTO ProductMenu VALUES(39, 1);
INSERT INTO ProductMenu VALUES(11, 3);
INSERT INTO ProductMenu VALUES(12, 3);
INSERT INTO ProductMenu VALUES(13, 3);
INSERT INTO ProductMenu VALUES(14, 3);
INSERT INTO ProductMenu VALUES(16, 3);
INSERT INTO ProductMenu VALUES(17, 3);
INSERT INTO ProductMenu VALUES(18, 3);
INSERT INTO ProductMenu VALUES(19, 3);
INSERT INTO ProductMenu VALUES(20, 3);
INSERT INTO ProductMenu VALUES(21, 3);
INSERT INTO ProductMenu VALUES(22, 3);
INSERT INTO ProductMenu VALUES(23, 3);
INSERT INTO ProductMenu VALUES(24, 3);
INSERT INTO ProductMenu VALUES(25, 3);
INSERT INTO ProductMenu VALUES(26, 3);
INSERT INTO ProductMenu VALUES(27, 4);
INSERT INTO ProductMenu VALUES(28, 4);
INSERT INTO ProductMenu VALUES(29, 4);
INSERT INTO ProductMenu VALUES(30, 4);
INSERT INTO ProductMenu VALUES(31, 4);
INSERT INTO ProductMenu VALUES(32, 4);
INSERT INTO ProductMenu VALUES(33, 5);
INSERT INTO ProductMenu VALUES(34, 5);
INSERT INTO ProductMenu VALUES(35, 5);
INSERT INTO ProductMenu VALUES(36, 5);
INSERT INTO ProductMenu VALUES(37, 5);
INSERT INTO ProductMenu VALUES(38, 5);
INSERT INTO ProductMenu VALUES(39, 5);
INSERT INTO ProductMenu VALUES(40, 5);
INSERT INTO ProductMenu VALUES(41, 5);
INSERT INTO ProductMenu VALUES(42, 5);
INSERT INTO ProductMenu VALUES(43, 7);
INSERT INTO ProductMenu VALUES(44, 7);
INSERT INTO ProductMenu VALUES(45, 7);
INSERT INTO ProductMenu VALUES(46, 7);
INSERT INTO ProductMenu VALUES(47, 7);
INSERT INTO ProductMenu VALUES(48, 7);
INSERT INTO ProductMenu VALUES(49, 7);
INSERT INTO ProductMenu VALUES(50, 7);
INSERT INTO ProductMenu VALUES(51, 7);
INSERT INTO ProductMenu VALUES(52, 7);
INSERT INTO ProductMenu VALUES(53, 7);
INSERT INTO ProductMenu VALUES(54, 7);
INSERT INTO ProductMenu VALUES(55, 7);
INSERT INTO ProductMenu VALUES(56, 7);
INSERT INTO ProductMenu VALUES(57, 7);
INSERT INTO ProductMenu VALUES(58, 8);
INSERT INTO ProductMenu VALUES(59, 8);
INSERT INTO ProductMenu VALUES(60, 8);
INSERT INTO ProductMenu VALUES(61, 8);
INSERT INTO ProductMenu VALUES(62, 8);
INSERT INTO ProductMenu VALUES(63, 8);
INSERT INTO ProductMenu VALUES(64, 8);
INSERT INTO ProductMenu VALUES(65, 8);
INSERT INTO ProductMenu VALUES(66, 8);
INSERT INTO ProductMenu VALUES(67, 8);
INSERT INTO ProductMenu VALUES(68, 8);
INSERT INTO ProductMenu VALUES(69, 8);
INSERT INTO ProductMenu VALUES(70, 8);
INSERT INTO ProductMenu VALUES(71, 8);

/* ---------------------------------------------------------------
 segundo restaurante
-----------------------------------------------------------------*/

INSERT INTO Restaurant VALUES(2, "Pans & Company (Arrábida Shopping)", "939939938", 2.40, 25,"Praceta de Henrique Moreira 244, 4400-436 Vila Nova de Gaia", 35, "low-cost");

INSERT INTO RestaurantCategory VALUES(2, 2);
INSERT INTO RestaurantCategory VALUES(2, 3);
INSERT INTO RestaurantCategory VALUES(2, 5);
INSERT INTO RestaurantCategory VALUES(2, 7);
INSERT INTO RestaurantCategory VALUES(2, 8);
INSERT INTO RestaurantCategory VALUES(2, 9);
INSERT INTO RestaurantCategory VALUES(2, 11);

INSERT INTO Menu VALUES(9, "FUN! COMBOS", 2);
INSERT INTO Menu VALUES(10, "FUN! FRITAS", 2);
INSERT INTO Menu VALUES(11, "FUN! BOX", 2);
INSERT INTO Menu VALUES(12, "MENUS PANS", 2);
INSERT INTO Menu VALUES(13, "MENUS VEGGIS&VEGANS", 2);
INSERT INTO Menu VALUES(14, "COMPLEMENTOS", 2);
INSERT INTO Menu VALUES(15, "SANDES", 2);
INSERT INTO Menu VALUES(16, "SANDES VEGGIES&VEGANS", 2);
INSERT INTO Menu VALUES(17, "Salada", 2);
INSERT INTO Menu VALUES(18, "Molhos Dip", 2);

INSERT INTO Product VALUES(211, "McRoyal ® Cheese", 5.50);

INSERT INTO ProductMenu VALUES(211, 9);

INSERT INTO RestaurantOwner VALUES(1, 1);

INSERT INTO UserOrder VALUES(1, "2022-06-06", "waiting", 1, 1,"rua daqui",NULL);
INSERT INTO UserOrder VALUES(2, "2022-06-09", "waiting", 1, 1,"rua dali",NULL);
INSERT INTO UserOrder VALUES(3, "2022-06-23", "waiting", 1, 1,"rua dacola",NULL);

INSERT INTO OrderProduct VALUES(1, 1, 1);
INSERT INTO OrderProduct VALUES(1, 3, 3);
INSERT INTO OrderProduct VALUES(1, 12, 2);
INSERT INTO OrderProduct VALUES(1, 13, 2);
INSERT INTO OrderProduct VALUES(1, 22, 1);

INSERT INTO OrderProduct VALUES(2, 2, 1);
INSERT INTO OrderProduct VALUES(2, 4, 3);
INSERT INTO OrderProduct VALUES(2, 14, 2);
INSERT INTO OrderProduct VALUES(2, 16, 2);
INSERT INTO OrderProduct VALUES(2, 23, 1);

INSERT INTO OrderProduct VALUES(3, 6, 1);
INSERT INTO OrderProduct VALUES(3, 7, 3);
INSERT INTO OrderProduct VALUES(3, 8, 2);
INSERT INTO OrderProduct VALUES(3, 26, 2);
INSERT INTO OrderProduct VALUES(3, 30, 1);

/**

**/

INSERT INTO Restaurant VALUES(3, "Art Pizza (Boavista)", "929939938", 1.00, 1, "Rua X - Boavista", 4, "med-cost");
INSERT INTO Restaurant VALUES(4, "Churrasqueira Paraíso", "929639938", 0, 45, "Rua Do Paraíso 250, 4000", 60, "high-cost");
INSERT INTO Restaurant VALUES(5, "Taco Bell (Arrábida Shopping)", "929929938", 1.50, 15, "C.C. Arrabida Shopping, Porto, 4400", 20, "med-cost");

INSERT INTO Review VALUES(7, 5, "nice", "2022-10-02", "", 1, 3);
INSERT INTO Review VALUES(8, 0, "bad", "2022-10-02", "", 1, 4);