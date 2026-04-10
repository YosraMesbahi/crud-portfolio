<?php

class Database {

    private $host = "mysql-yosra-sae203.alwaysdata.net";
    private $dbname = "yosra-sae203_socials-medias";
    private $user = "yosra-sae203";
    private $pass = "M@s@bih4620!+";

    public $conn;

    public function connect() {

        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Connexion échouée : " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }
}