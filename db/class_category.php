<?php
    declare(strict_types = 1);

    class Category{
        public int $id;
        public string $name;
        
        public function __construct(array $categ){
            $this->id = (int)$categ['idCategory'];
            $this->name = $categ['name'];
        }

        static function getCategories(PDO $db) : array{
            $stmt = $db->prepare('SELECT * FROM Category');
            $stmt->execute();

            $res = array();
            while($r = $stmt->fetch()){
                $res[] = new Category($r);
            }
            return $res;
        }

        static function getImage(int $id) : string {
            return "../imgs/categ/" . $id . ".png";
        }
    }
?>