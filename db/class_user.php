<?php
    declare(strict_types = 1);

    class User{
        public int $id;
        public string $email;
        public string $firstName;
        public string $lastName;
        public string $address;
        public string $phoneNumber;

        public function __construct(array $user){
            $this->id = (int)$user['idUser'];
            $this->email = $user['email'];
            $this->firstName = $user['firstName'];
            $this->lastName = $user['lastName'];
            $this->address = $user['address'];
            $this->phoneNumber = $user['phoneNumber'];
        }

        function name() : string{
            return $this->firstName . ' ' . $this->lastName;
        }
        function address() : string{
            return $this->address;
        }
        function id() : int{
            return $this->id;
        }

        static function getName(PDO $db, int $id) : string{
            $stmt = $db->prepare(
                'SELECT firstName, lastName
                FROM User 
                WHERE idUser = ?
            ');
            $stmt->execute(array($id));

            $ret = $stmt->fetch();
            return $ret['firstName'] . ' ' . $ret['lastName'];
        }

        static function getAddress(PDO $db, int $id) : string{
            $stmt = $db->prepare(
                'SELECT address
                FROM User 
                WHERE idUser = ?
            ');
            $stmt->execute(array($id));

            $ret = $stmt->fetch();
            return $ret['address'];
        }

        static function getUserWithPassword(PDO $db, string $email, string $password) : ?User {
            $stmt = $db->prepare('
              SELECT *
              FROM User 
              WHERE lower(email) = ?
            ');
            $stmt->execute(array(strtolower($email)));

            $user = $stmt->fetch();
            if($user && password_verify($password, $user['password']))
                return new User($user);
            else
                return null;
        }

        static function getExistingUser(PDO $db, string $email, string $phoneNumber) : ?User{
            $stmt = $db->prepare('
            SELECT *
            FROM User 
            WHERE lower(email) = ? OR phoneNumber = ?
          ');
    
          $stmt->execute(array(strtolower($email), $phoneNumber));
      
          if ($user = $stmt->fetch())
              return new User($user);
          else 
              return null;
        }

        static function getCurrentUser(PDO $db, int $id) : ?User {
            $stmt = $db->prepare('
            SELECT *
            FROM User 
            WHERE idUser = ?');
            $stmt->execute(array($id));

            if($user = $stmt->fetch()) {
                return new User($user);
            }
            else {
                return null;
            }
        }

        static function register(PDO $db, array $formInformation){
            $stmt = $db->prepare('
                INSERT INTO User
                VALUES(NULL, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute(array(
                $formInformation['email'],
                password_hash($formInformation['password'], PASSWORD_DEFAULT, ['cost' => 12]),
                $formInformation['first_name'],
                $formInformation['last_name'],
                $formInformation['address'],
                $formInformation['phone_number']
            ));
        }

        static function canReviewRestaurant(PDO $db, int $idRestaurant, int $idUser) : bool{
            $stmt = $db->prepare('
            SELECT *
            FROM UserOrder 
            WHERE idUser = ? AND idRestaurant = ?');
            $stmt->execute(array($idUser, $idRestaurant));

            $hasAnyOrder = !empty($stmt->fetch());

            $stmt = $db->prepare('
            SELECT *
            FROM Review 
            WHERE idUser = ? AND idRestaurant = ? AND comment <> ""');
            $stmt->execute(array($idUser, $idRestaurant));

            $hasAlreadyReviewed = !empty($stmt->fetch());

            return $hasAnyOrder && !$hasAlreadyReviewed;
        }

        static function updateUser(PDO $db, $firstName, $lastName, $email, $adress, $phone, int $id) {
            $stmt = $db->prepare('
            Update User set firstName = ?, lastName = ?, address = ?, phoneNumber = ?, email = ?
            WHERE idUser = ?
            ');

            $stmt->execute(array($firstName, $lastName, $adress, $phone,$email,$id));
        }

        static function getImage(int $id) : string {
            $path = "../imgs/user/" . $id . ".png";
            if(file_exists($path)) {
                return "../imgs/user/" . $id . ".png";
            }
            else {
                return "../imgs/user/def.png";
            }
        }

        static function changePassword(PDO $db, int $id, string $newPassword) {
            $stmt = $db->prepare('
            Update User set password = ?
            WHERE idUser = ?
            ');

            $stmt->execute(array(password_hash($newPassword,PASSWORD_DEFAULT, ['cost' => 12]),$id));
        }
    }
?>