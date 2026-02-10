<?php

class OperatiiDB{

    //CRUD Interface catre baza de date
    public static function read($tabel, $query, $option) {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();

        $sql = "SELECT $query FROM $tabel $option";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function create($tabel, $valori){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $coloaneNeformatate = implode(",",array_keys($valori));
        $coloane = array_keys($valori);
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = ":" . $coloane[$i];
        }
        $coloane = implode(", ", $coloane);

        $sql = "INSERT INTO $tabel ($coloaneNeformatate) VALUES ($coloane)";
        echo $sql;
        echo var_dump($valori);
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);  

        return $conn->lastInsertId();
    }

    public static function update($tabel, $valori, $conditie, $paramCond){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $coloane = array_keys($valori);
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = $coloane[$i] . "=:" . $coloane[$i];
        }
        $coloane = implode(", ", $coloane);

        $sql = "UPDATE $tabel SET $coloane WHERE $conditie";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($valori,$paramCond));  
    }

    public static function delete($tabel, $conditie, $param){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();

        $sql = "DELETE FROM $tabel WHERE $conditie";
        $stmt = $conn->prepare($sql);
        $stmt->execute($param); //trebuie sa adaugam parametrii ca sa inlocuim id-ul
    }
}
