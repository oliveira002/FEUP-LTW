<?php
    declare(strict_types = 1);
    require_once('../db/class_user.php');
    require_once('../db/class_restaurant.php');

    class Order{
        public int $id;
        public string $date;
        public string $state;
        public int $idUser;
        public int $idRestaurant;
        public float $price;
        
        public function __construct(array $order){
            $this->id = (int)$order['idOrder'];
            $this->date = $order['date'];
            $this->state = $order['state'];
            $this->idUser = (int)$order['idUser'];
            $this->idRestaurant = (int)$order['idRestaurant'];
            $this->price = (float)$order['price'];
        }

        static function getOrders(PDO $db, int $idUser) : array{
            $stmt = $db->prepare(
                'SELECT * 
                FROM UserOrder
                WHERE idUser = ?');
            $stmt->execute(array($idUser));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Order($r);
            }
            return $res;
        }

        static function getOrdersToDeliver(PDO $db) : array{
            $stmt = $db->prepare(
                'SELECT * 
                FROM UserOrder
                WHERE state = "ready"');
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Order($r);
            }
            return $res;
        }

        static function getRestaurantOrders(PDO $db, int $idRestaurant) : array{
            $stmt = $db->prepare(
                'SELECT * 
                FROM UserOrder
                WHERE idRestaurant = ?');
            $stmt->execute(array($idRestaurant));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Order($r);
            }
            return $res;
        }

        static function getOrder(PDO $db, int $id){
            $stmt = $db->prepare(
                'SELECT * 
                FROM UserOrder
                WHERE idOrder = ?');
            $stmt->execute(array($id));
            return new Order($stmt->fetch());
        }

        static function changeState(PDO $db, int $orderID, string $newState){
            $stmt = $db->prepare(
                'UPDATE UserOrder
                SET state = ?
                WHERE idOrder = ?'
            );
            $stmt->execute(array(strtolower($newState), $orderID));
        }

        static function PushOrderDB(PDO $db, array $cartList, FLOAT $total,int $idRestaurant, User $user,string $adress = '') : string{
            if($adress == ''){
                $adress = $user->address();
            }
            $stmt = $db->prepare('INSERT INTO UserOrder (date,state,idUser,idRestaurant,address,price) VALUES(:date, :status, :idUser , :idRestaurant ,:adress,:price)');
            $stmt->execute(array(
                ':date' => date('Y-m-d'),
                ':status' => 'waiting',
                ':idUser' => $user->id(),
                ':idRestaurant' => $idRestaurant,
                ':adress' => $adress,
                ':price' => $total,

            ));
            $rowID = $db->lastInsertId('UserOrder');

            foreach ($cartList as $entry){
                $arr = explode(":",$entry);
                $stmt = $db->prepare('INSERT INTO OrderProduct VALUES(:idOrder, :idProduct, :quantity)');
                $stmt->execute(array(
                    ':idOrder' => $rowID,
                    ':idProduct' => $arr[0],
                    ':quantity' => $arr[1],
                ));
            }

            return $rowID;
        }
    }
?>