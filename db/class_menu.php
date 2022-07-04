<?php
    declare(strict_types = 1);

    require_once("class_product.php");

    class Menu{
        public int $id;
        public string $name;
        public array $products;
        
        public function __construct(array $menu){
            $this->id = (int)$menu['idMenu'];
            $this->name = $menu['name'];
            $this->products = $menu['prod'];
        }

        static function getMenus(PDO $db, int $idRestaurant) : array{
            $stmt = $db->prepare('SELECT * FROM Menu WHERE idRestaurant = :id');
            $stmt->bindParam(':id', $idRestaurant);
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $r['prod'] = Product::getProducts($db, strval($r['idMenu']));
                $res[] = new Menu($r);
            }
            return $res;
        }

        static function getMenu(PDO $db, int $id) : Menu{
            $stmt = $db->prepare(
                'SELECT *
                FROM Menu
                WHERE idMenu = ?'
            );
            $stmt->execute(array($id));
            $r = $stmt->fetch();
            $r['prod'] = Product::getProducts($db, strval($r['idMenu']));
            return new Menu($r);
        }

        static function create(PDO $db, array $fields) : int{
            $stmt = $db->prepare('
            INSERT INTO Menu 
            VALUES(NULL, ?, ?)');
            $stmt->execute($fields);
            return intval($db->lastInsertId());
        }

        static function erase(PDO $db, int $id){
            $menuProducts = Product::getProducts($db, strval($id));
            foreach($menuProducts as $prod){
                Product::removeFromMenu($db, $prod->idProduct, $id);
            }

            $stmt = $db->prepare(
                'DELETE FROM Menu
                WHERE idMenu = ?');
            $stmt->execute(array($id));
        }

        static function exists(PDO $db, int $restID, int $menuID) : bool{
            $stmt = $db->prepare(
                'SELECT *
                FROM Menu
                WHERE idRestaurant = ? and idMenu = ?');
            $stmt->execute(array($restID, $menuID));
            return !empty($stmt->fetch());
        }
    }
?>