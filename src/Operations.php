<?php
require_once("MyGuests.php");
class Operations
{
    private $conn;
    public function __construct(){
        $this->openConnection();
    }
    public function openConnection()
    {
        // Database configuration (match your docker-compose.yml)
        $host = 'db';          // The service name in docker-compose (not 'localhost')
        $db   = 'mydb';        // Database name
        $user = 'user';        // MySQL username
        $pass = 'userpass';    // MySQL password
        $charset = 'utf8mb4';

        // DSN (Data Source Name)
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        // PDO options for better error handling and performance
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
        ];

        // Create a PDO instance (connect to the database)
        $this->conn = new PDO($dsn, $user, $pass, $options);
    }//openConnection
    public function closeConnection()
    {
        $this->conn = null;
    }
    function getMyGuest($id){
        $sqlString = "select id, firstname, lastname, email, reg_date from MyGuests where id=?";
        $query = $this->conn->prepare($sqlString);
        $query->execute([$id]);
        $myGuest = $query->fetchObject("MyGuests");
        return $myGuest;
    }
    function getMyGuest2($id){
        $sqlString = "select id, firstname, lastname, email, reg_date from MyGuests where id=?";
        $query = $this->conn->prepare($sqlString);
        $query->execute([$id]);
        //Fetch the row into the MyGuests object
        $tableGuest = $query->fetch();
        $obx = new MyGuests();
        $obx->setId($tableGuest["id"]);
        $obx->setFirstName($tableGuest["firstname"]);
        $obx->setLastName($tableGuest["lastname"]);
        $obx->setEmail($tableGuest["email"]);
        $obx->setRegDate($tableGuest["reg_date"]);
        return $obx;
    }
    function getAllMyGuests2(){
        $sqlString = "select id, firstname, lastname, email, reg_date from MyGuests";
        $query = $this->conn->prepare($sqlString);
        $query->execute();
        //Fetch the row into the MyGuests object
        $guestList = array(); //An empty list
        while($myGuest = $query->fetch()){ //Fetch a row from the table
            $obx = new MyGuests();
            $obx->setId($myGuest["id"]);
            $obx->setFirstName($myGuest["firstname"]);
            $obx->setLastName($myGuest["lastname"]);
            $obx->setEmail($myGuest["email"]);
            $obx->setRegDate($myGuest["reg_date"]);
            $guestList[] = $obx;
        }//while
        return $guestList;
    }
    function addMyGuests($myGuest){
        try{
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("insert into MyGuests (firstname, lastname, email, reg_date) VALUES (?, ?, ?, ?)");
            $firstName = $myGuest->getFirstName();
            $lastName = $myGuest->getLastName();
            $email = $myGuest->getEmail();
            $reg_date = $myGuest->getRegDate();
            $stmt->execute([$firstName, $lastName, $email, $reg_date]);
            $numberOfAddedRows = $stmt->rowCount();
            $this->conn->commit();
            return $numberOfAddedRows;
        }
        catch(PDOException $erro){
            // roll back the transaction if something failed
            $this->conn->rollback();
            throw $erro;
        }
    }//addMyGuests
    function updateMyGuests($myGuest){
        try{
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("update MyGuests set firstname=?, lastname=?, email=?, reg_date=? where id=?");
            $firstName = $myGuest->getFirstName();
            $lastName = $myGuest->getLastName();
            $email = $myGuest->getEmail();
            $reg_date = $myGuest->getRegDate();
            $id = $myGuest->getId();
            $stmt->execute([$firstName, $lastName, $email, $reg_date, $id]);
            $numberOfAddedRows = $stmt->rowCount();    
            $this->conn->commit();
            return $numberOfAddedRows;
        }
        catch(PDOException $e) {
            // roll back the transaction if something failed
            $this->conn->rollback();
            throw $e;
        }
    }//update
    function deleteMyGuests($id){
        try{
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("delete from MyGuests where id=?");
            $stmt->execute([$id]);
            $numberOfAddedRows = $stmt->rowCount();    
            $this->conn->commit();
            return $numberOfAddedRows;
        }
        catch(PDOException $e) {
            // roll back the transaction if something failed
            $this->conn->rollback();
            throw $e;
        }
    }//delete
}//class
