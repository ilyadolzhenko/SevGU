<?php

include "ClientBooks.php";

class ClientBooksRepository {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new ClientBooks();
        $result->id = $row["id_книги"];
        $result->name = $row["название"];
        $result->authors = $row["авторы"];
        $result->categories = $row["направление"];
        $result->publish = $row["издательство"];
        $result->date_create = $row["год_издания"];
        $result->number_pages = $row["количество_страниц"];
        return $result;
    }

    public function getAll($filter) {
        $id = $filter["id"];
        $name = "%" . $filter["name"] . "%";
        $authors = "%" . $filter["authors"] . "%";
        $categories = "%" . $filter["categories"] . "%";
        $publish = "%" . $filter["publish"] . "%";
        $date_create = "%" . $filter["date_create"] . "%";
        $number_pages = $filter["number_pages"];

        $sql = "SELECT книги.id_книги, книги.название,
                GROUP_CONCAT(distinct авторы.фио SEPARATOR ', ') as авторы,
                направления.описание 'направление', издательства.название 'издательство', книги.год_издания, книги.количество_страниц
                FROM книги
                LEFT JOIN авторы_книги ON авторы_книги.id_книги = книги.id_книги
                LEFT JOIN авторы ON авторы_книги.id_автора = авторы.id_автора
                LEFT JOIN направления ON направления.id_направления = книги.id_направления
                LEFT JOIN издательства ON издательства.id_издательства = книги.id_издательства
                WHERE (:id = 0 OR книги.id_книги = :id) AND книги.название LIKE :name AND авторы.фио LIKE :authors 
                    AND направления.описание LIKE :categories AND издательства.название LIKE :publish
                    AND книги.год_издания LIKE :date_create AND (:number_pages = 0 OR книги.количество_страниц = :number_pages)
                GROUP BY книги.id_книги";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id);
        $q->bindParam(":name", $name);
        $q->bindParam(":authors", $authors);
        $q->bindParam(":categories", $categories);
        $q->bindParam(":publish", $publish);
        $q->bindParam(":date_create", $date_create);
        $q->bindParam(":number_pages", $number_pages);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }
}

?>