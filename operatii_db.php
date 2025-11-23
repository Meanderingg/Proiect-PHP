<?php
class Database {
    public $conn;
    public function __construct($host,$user,$pass,$dbname){
        $this->conn=new mysqli($host,$user,$pass,$dbname);
        if($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);
    }
    public function create($table,$data){
        $fields=implode(", ",array_keys($data));
        $values="'".implode("','",array_values($data))."'";
        return $this->conn->query("INSERT INTO $table ($fields) VALUES ($values)");
    }
    public function read($table,$where="1"){
        $result=$this->conn->query("SELECT * FROM $table WHERE $where");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function update($table,$data,$where){
        $set=[];
        foreach($data as $k=>$v){ $set[]="$k='$v'"; }
        return $this->conn->query("UPDATE $table SET ".implode(",",$set)." WHERE $where");
    }
    public function delete($table,$where){
        return $this->conn->query("DELETE FROM $table WHERE $where");
    }
}
?>