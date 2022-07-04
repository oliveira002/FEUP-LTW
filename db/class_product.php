<?php
    declare(strict_types = 1);

    class Product{
        public int $idProduct;
        public string $name;
        public string $price;
        public int $quantity;
        public bool $favorite;
        
        public function __construct(array $p){
            $this->idProduct = (int)$p['idProduct'];
            $this->name = $p['name'];
            $this->price = number_format((float)$p['price'], 2);
            $this->quantity = array_key_exists('quantity', $p) ? (int)$p['quantity'] : 1;
            $this->favorite = false;
        }

        function fillFavorite(PDO $db, int $user){
            $this->favorite = self::isFavorite($db, $this->idProduct, $user);
        }

        static function getProducts(PDO $db, string $idMenu) : array{
            $stmt = $db->prepare('SELECT * FROM ProductMenu NATURAL JOIN Product WHERE idMenu = :id');
            $stmt->bindParam(':id', $idMenu);
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Product($r);
            }
            return $res;
        }

        static function getProductsOfRestaurant(PDO $db, int $idRestaurant) : array{
            /*
            $stmt = $db->prepare(
                'SELECT DISTINCT Product.idProduct, Product.name, Product.price
                FROM Product, Menu, Restaurant, ProductMenu
                WHERE Restaurant.idRestaurant = ?
                AND Menu.idRestaurant = Restaurant.idRestaurant
                AND ProductMenu.idMenu = Menu.idMenu
                AND Product.idProduct = ProductMenu.idProduct'
            );
            */
            $stmt = $db->prepare(
                'SELECT DISTINCT Product.*
                FROM Restaurant JOIN Menu USING(idRestaurant) 
                                JOIN ProductMenu USING(idMenu)
                                JOIN Product USING(idProduct)
                WHERE idRestaurant = ?'
            );
            $stmt->execute(array($idRestaurant));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Product($r);
            }
            return $res;
        }

        static function getProductRestaurantName(PDO $db, int $id) : string{
            $stmt = $db->prepare(
                'SELECT Distinct Restaurant.name
                FROM Menu, Restaurant, ProductMenu
                WHERE ProductMenu.idProduct = ?
                AND Menu.idRestaurant = Restaurant.idRestaurant
                AND ProductMenu.idMenu = Menu.idMenu'
            );
            $stmt->execute(array($id));

            $res = $stmt->fetch();
            return $res["name"];
        }

        static function getProductRestaurantAddress(PDO $db, int $id) : string{
            $stmt = $db->prepare(
                'SELECT Distinct Restaurant.address
                FROM Menu, Restaurant, ProductMenu
                WHERE ProductMenu.idProduct = ?
                AND Menu.idRestaurant = Restaurant.idRestaurant
                AND ProductMenu.idMenu = Menu.idMenu'
            );
            $stmt->execute(array($id));

            $res = $stmt->fetch();
            return $res["address"];
        }

        static function getProductRestaurantId(PDO $db, int $id) : int{
            $stmt = $db->prepare(
                'SELECT Distinct Restaurant.idRestaurant
                FROM Menu, Restaurant, ProductMenu
                WHERE ProductMenu.idProduct = ?
                AND Menu.idRestaurant = Restaurant.idRestaurant
                AND ProductMenu.idMenu = Menu.idMenu'
            );
            $stmt->execute(array($id));

            $res = $stmt->fetch();
            return intval($res["idRestaurant"]);
        }

        static function getProduct(PDO $db, int $id) : Product{
            $stmt = $db->prepare(
                'SELECT *
                FROM Product
                WHERE idProduct = ?'
            );
            $stmt->execute(array($id));
            return new Product($stmt->fetch());
        }

        static function create(PDO $db, array $fields) : Product{
            $stmt = $db->prepare('
            INSERT INTO Product 
            VALUES(NULL, ?, ?)');
            $stmt->execute(array($fields[0], $fields[1]));

            $prodID = intval($db->lastInsertId());

            self::addToMenu($db, $prodID, intval($fields[2]));

            return self::getProduct($db, $prodID);
        }

        static function erase(PDO $db, int $id){
            $stmt = $db->prepare(
                'DELETE FROM Product
                WHERE idProduct = ?'
            );
            $stmt->execute(array($id));

            // Remove images
            $img = "../imgs/prod/" . strval($id) . ".webp";
            unlink($img);
        }

        static function isOnMenu(PDO $db, int $prodID, int $menuID) : bool{
            $stmt = $db->prepare(
                'SELECT *
                FROM ProductMenu
                WHERE idProduct = ? AND idMenu = ?'
            );
            $stmt->execute(array($prodID, $menuID));
            return !empty($stmt->fetch());
        }

        static function addToMenu(PDO $db, int $prodID, int $menuID){
            $stmt = $db->prepare(
                'INSERT INTO ProductMenu
                VALUES(?, ?)');
            $stmt->execute(array($prodID, $menuID));
        }

        static function removeFromMenu(PDO $db, int $prodID, int $menuID){
            // Remove Product/Menu connection
            $stmt = $db->prepare(
                'DELETE FROM ProductMenu
                WHERE idProduct = ? and idMenu = ?');
            $stmt->execute(array($prodID, $menuID));

            // Before erasing the product check if it is on another menu
            $stmt = $db->prepare(
                'SELECT *
                FROM ProductMenu
                WHERE idProduct = ?');
            $stmt->execute(array($prodID));

            // If the product is a orphan
            if(empty($stmt->fetch())){
                // Check if the product is connected to any order
                $stmt = $db->prepare(
                    'SELECT *
                    FROM OrderProduct
                    WHERE idProduct = ?');
                $stmt->execute(array($prodID));

                // Exists orders with this product, we shouldn't remove it
                if(!empty($stmt->fetch()))
                    return;
                // We can completly remove the product
                else
                    self::erase($db, $prodID);
            }
        }

        static function isFavorite(PDO $db, int $id, int $user) : bool{
            $stmt = $db->prepare(
                'SELECT *
                FROM FavoriteProduct
                WHERE idUser = ? AND idProduct = ?'
            );
            $stmt->execute(array($user, $id));

            return !empty($stmt->fetch());
        }

        static function favorite(PDO $db, int $id, int $user) : bool{
            $exists = self::isFavorite($db, $id, $user);

            if($exists){
                $stmt = $db->prepare(
                    'DELETE FROM FavoriteProduct
                    WHERE idUser = ? AND idProduct = ?');
                $stmt->execute(array($user, $id));
                    
                return false;
            } else{
                $stmt = $db->prepare(
                    'INSERT INTO FavoriteProduct
                    VALUES(?, ?)');
                $stmt->execute(array($user, $id));

                return true;
            }
        } 

        static function getFavorites(PDO $db, int $user) : array{
            $stmt = $db->prepare(
                'SELECT *
                FROM FavoriteProduct NATURAL JOIN Product
                WHERE idUser = ?'
            );
            $stmt->execute(array($user));
            
            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Product($r);
            }
            return $res;
        }

        static function getOrderProducts(PDO $db, int $idOrder) : array{
            $stmt = $db->prepare('SELECT * FROM OrderProduct NATURAL JOIN Product WHERE idOrder = ?');
            $stmt->execute(array($idOrder));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Product($r);
            }
            return $res;
        }

        static function getImage(int $id) : string {
            return "../imgs/prod/" . $id . ".webp";
        }
    }
?>