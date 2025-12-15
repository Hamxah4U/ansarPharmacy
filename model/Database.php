<?php
    class Database{
      public $conn;
      public function __construct()
      {
        $dsn = 'mysql:host=localhost;dbname=ansarpharmacy'; //sfgeorgn_shafabillingdb  billing_db
          $dbuser = 'root';
          $dbpass = '';
          try {
            $this->conn = new PDO($dsn, $dbuser, $dbpass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'connected';
          } catch (PDOException $e) {
            die("DB Connection failed:" .$e->getMessage());
          }         
      }

      public function query($sql){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
      }

      public function checkExist($sql, $param = []){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($param);
        return $stmt;
      }
    }

    $db = new Database();
    $storeName = 'ANSAR PHARMACY & STORE';
    $subhead = 'GLOBAL SERVICE LTD';
    $state = 'Address: 83/19 Mango Street Jos Plateau State.';
    $phone = '07051383610';
    
?>