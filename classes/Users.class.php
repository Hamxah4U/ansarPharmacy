<?php
  require 'model/Database.php';

  class Users{
    private $db;
    public function __construct($db)
    {
      $this->db = $db;     
    }

    public function allusers(){
      try{
        $stmt = $this->db->query('SELECT * FROM `users_tbl` ');
        return $stmt;
      }catch(PDOException $e){
        die('Error:'. $e->getMessage());
      }
    }
  }
    
    
?>