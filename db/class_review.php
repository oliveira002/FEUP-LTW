<?php
    declare(strict_types = 1);

    require_once("class_product.php");

    class Review{
        public int $id;
        public string $username;
        public string $date;
        public string $comment;
        public int $rating;
        public string $answer;
        
        public function __construct(array $review){
            $this->id = intval($review['idReview']);
            $this->username = $review['username'];
            $this->date = $review['rDate'];
            $this->comment = $review['comment'];
            $this->rating = (int)$review['rating'];
            $this->answer = is_null($review['answer']) ? "" : $review['answer'];
        }

        static function getReviews(PDO $db, int $idRestaurant) : array{
            $stmt = $db->prepare('SELECT idReview, firstName, lastName, rDate, comment, rating, answer FROM Review NATURAL JOIN User WHERE idRestaurant = :id');
            $stmt->bindParam(':id', $idRestaurant);
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $r['username'] = $r['firstName'] . " " . $r['lastName'];
                $res[] = new Review($r);
            }
            return $res;
        }

        static function addAnswer(PDO $db, int $reviewID, string $text){
            $stmt = $db->prepare(
                'UPDATE Review
                SET answer = ?
                WHERE idReview = ?'
            );
            $stmt->execute(array($text, $reviewID));
        }

        static function create(PDO $db, int $userID, int $restID, string $text, int $rating){
            $stmt = $db->prepare(
                'INSERT INTO Review
                VALUES(NULL, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute(array($rating, $text, date('y-m-d'), "", $userID, $restID));
        }
    }
?>