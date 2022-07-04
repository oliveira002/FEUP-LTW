<?php
    declare(strict_types = 1);

    require_once("class_menu.php");

    class Restaurant{
        public int $id;
        public string $name;
        public string $phoneNumber;
        public string $rating;
        public int $numReviews;
        public string $tax;
        public string $minTime;
        public string $maxTime;
        public string $address;
        public bool $favorite;

        public function __construct(PDO $db, array $rest){
            $this->id = (int)$rest['idRestaurant'];
            $this->name = $rest['name'];
            $this->phoneNumber = $rest['phoneNumber'];

            $ratingInfo = self::calculateRating($db, strval($rest['idRestaurant']));
            
            $this->rating = $ratingInfo['rating'];
            $this->numReviews = $ratingInfo['numReviews'];

            $this->tax = number_format((float)$rest['tax'], 2);
            $this->minTime = strval($rest['minTime']);
            $this->maxTime = strval($rest['maxTime']);
            $this->address = strval($rest['address']);

            $this->favorite = false;
        }

        function fillFavorite(PDO $db, int $user){
            $this->favorite = self::isFavorite($db, $this->id, $user);
        }

        static function calculateRating(PDO $db, string $id) : array {
            $stmt = $db->prepare('SELECT * FROM Review WHERE idRestaurant = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $reviews = $stmt->fetchAll();
            
            $res = array();

            $num = count($reviews);
            if($num == 0){
                $res['rating'] = "-";
                $res['numReviews'] = 0;
                return $res;
            }
            
            $sum = floatval(0);
            foreach($reviews as $r)
                $sum += (float)$r['rating'];

            $res['rating'] = number_format($sum / $num, 1);
            $res['numReviews'] = $num;
            return $res;
        }

        static function getRestaurants(PDO $db, int $count) : array {
            $stmt = $db->prepare('SELECT * FROM Restaurant LIMIT ?');
            $stmt->execute(array($count));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Restaurant($db, $r);
            }
            return $res;
        }

        static function getListRestaurants(PDO $db, int $count, string $order, string $priceGroup, int $maxTime, string $search, int $category) : array{
            $orderIndex = ['foryou' => 1, 'popular' => 9, 'time' => 7, 'rating' => 10];
            $desc = in_array($order, array('popular', 'rating'));
            $orderByPart = in_array($order, array('foryou', 'popular', 'time', 'rating')) 
            ? 'ORDER BY ' . $orderIndex[$order] . ($desc ? ' DESC ' : ' ')
            : ' ';
            
            $includeRestaurantesWithNullCategories = $category == 0;

            $stmt = $db->prepare(
                'SELECT SSR.*, sum(rating) / CAST(count(idRestaurant) as REAL) as sRating
                FROM(
                    SELECT SR.*, count(idOrder) as nOrders
                    FROM(
                        SELECT DISTINCT Restaurant.*
                        FROM Restaurant LEFT JOIN Menu USING(idRestaurant) LEFT JOIN ProductMenu USING(idMenu) LEFT JOIN Product USING(idProduct) LEFT JOIN RestaurantCategory USING(idRestaurant)
                        WHERE (Restaurant.name LIKE ? OR Product.name LIKE ?) AND (idCategory LIKE ?'
                        . ($includeRestaurantesWithNullCategories ? 'OR idCategory IS NULL) ' : ') ') .
                        'COLLATE NOCASE
                    ) as SR LEFT JOIN UserOrder USING(idRestaurant)
                    GROUP BY idRestaurant
                    ) as SSR LEFT JOIN Review USING(idRestaurant)
                WHERE maxTime < ?
                AND priceGroup LIKE ?
                GROUP BY idRestaurant '
                .
                $orderByPart
                .
                'LIMIT ?'
            );

            if($priceGroup === "none") $priceGroup = "%";
            if($category === 0) $category = "%";

            $stmt->execute(array('%' . $search . '%', '%' . $search . '%', $category, $maxTime, $priceGroup, $count));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Restaurant($db, $r);
            }
            return $res;
        }

        static function getRestaurantsWithCategory(PDO $db, int $categoryID, int $count) : array {
            $stmt = $db->prepare(
                'SELECT * 
                FROM RestaurantCategory NATURAL JOIN Restaurant 
                WHERE :idCateg = idCategory
                LIMIT :lim'
            );
            $stmt->bindParam('idCateg', $categoryID);
            $stmt->bindParam('lim', $count);
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Restaurant($db, $r);
            }
            return $res;
        }

        static function getRestaurant(PDO $db, int $id) : Restaurant {
            $stmt = $db->prepare('SELECT * FROM Restaurant WHERE idRestaurant = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return new Restaurant($db, $stmt->fetch());
        }

        function getCategories(PDO $db) : array{
            $stmt = $db->prepare('SELECT idCategory FROM RestaurantCategory WHERE idRestaurant = ?');
            $stmt->execute(array($this->id));
            return array_column($stmt->fetchAll(), 'idCategory');
        }

        function addCategory(PDO $db, int $categoryID){
            if(in_array($categoryID, self::getCategories($db)))
                return;

            $stmt = $db->prepare('
            INSERT INTO RestaurantCategory
            VALUES(?, ?)');
            $stmt->execute(array($this->id, $categoryID));
        }

        function removeCategory(PDO $db, int $categoryID){
            if(!in_array($categoryID, self::getCategories($db)))
                return;

            $stmt = $db->prepare('
            DELETE FROM RestaurantCategory
            WHERE idRestaurant = ? AND idCategory = ?');
            $stmt->execute(array($this->id, $categoryID));
        }

        function updateInformations(PDO $db, array $newInfo){
            $stmt = $db->prepare('
            UPDATE Restaurant
            SET name = ?,
            phoneNumber = ?,
            tax = ?,
            minTime = ?,
            address = ?,
            maxTime = ?
            WHERE idRestaurant = ?');
            array_push($newInfo, $this->id);
            $stmt->execute($newInfo);
        }

        static function isOwner(PDO $db, int $restaurantID, int $userID) : bool{
            $stmt = $db->prepare('SELECT * FROM RestaurantOwner WHERE idRestaurant = ? AND idUser = ?');
            $stmt->execute(array($restaurantID, $userID));
            return count($stmt->fetchAll()) > 0;
        }

        function addOwner(PDO $db, int $owner){
            $stmt = $db->prepare('
            INSERT INTO RestaurantOwner 
            VALUES(?, ?)');
            $stmt->execute(array($owner, $this->id));
        }

        static function create(PDO $db, array $fields) : Restaurant{
            $stmt = $db->prepare('
            INSERT INTO Restaurant 
            VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute($fields);
            return self::getRestaurant($db, intval($db->lastInsertId()));
        }

        function erase(PDO $db){
            $s1 = $db->prepare('DELETE FROM Restaurant WHERE idRestaurant = ?');
            $s2 = $db->prepare('DELETE FROM RestaurantCategory WHERE idRestaurant = ?');
            $s3 = $db->prepare('DELETE FROM RestaurantOwner WHERE idRestaurant = ?');

            $s1->execute(array($this->id));
            $s2->execute(array($this->id));
            $s3->execute(array($this->id));

            $s4 = $db->prepare('SELECT * FROM Menu WHERE idRestaurant = ?');
            $s5 = $db->prepare('DELETE FROM Review WHERE idRestaurant = ?');

            $s4->execute(array($this->id));
            foreach($s4->fetchAll() as $menu){
                Menu::erase($db, intval($menu['idMenu']));
            }

            $s5->execute(array($this->id));
        }

        static function isFavorite(PDO $db, int $id, int $user) : bool{
            $stmt = $db->prepare(
                'SELECT *
                FROM FavoriteRestaurant
                WHERE idUser = ? AND idRestaurant = ?'
            );
            $stmt->execute(array($user, $id));

            return !empty($stmt->fetch());
        }

        static function favorite(PDO $db, int $id, int $user) : bool{
            $exists = self::isFavorite($db, $id, $user);

            if($exists){
                $stmt = $db->prepare(
                    'DELETE FROM FavoriteRestaurant
                    WHERE idUser = ? AND idRestaurant = ?');
                $stmt->execute(array($user, $id));
                    
                return false;
            } else{
                $stmt = $db->prepare(
                    'INSERT INTO FavoriteRestaurant
                    VALUES(?, ?)');
                $stmt->execute(array($user, $id));

                return true;
            }
        } 

        static function getFavorites(PDO $db, int $user) : array{
            $stmt = $db->prepare(
                'SELECT *
                FROM FavoriteRestaurant NATURAL JOIN Restaurant
                WHERE idUser = ?'
            );
            $stmt->execute(array($user));

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Restaurant($db, $r);
            }
            return $res;
        }

        static function getImage(int $id) : string {
            return "../imgs/rest/background_" . strval($id) . ".png";
        }

        static function getLogo(int $id) : string{
            return "../imgs/rest/logo_" . strval($id) . ".png";
        }
    }
?>