<?php
    require_once("Student.php");
    class Operations{
        private $conn;
        public function __construct(){
            $this->openConnection();
        }
        public function openConnection() {
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

            try {
                $this->conn = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                echo "❌ Database connection failed: " . $e->getMessage();
            }
        }
        public function closeConnection(){
            $this->conn = null;
        }

        public function getAllStudents(): array {
            $sqlString = "select * from Student";
            $query = $this->conn->prepare($sqlString);
            $query->execute();

            $list = array();

            while($qElement = $query->fetch()){
                $student = new Student();
                $student->setId($qElement['id']);
                $student->setDni($qElement['dni'] ?? null);
                $student->setName($qElement['name'] ?? null);
                $student->setSurname($qElement['surname'] ?? null);
                $student->setAge($qElement['age'] ?? null);
                $list[] = $student;
            }
            return $list;
        }

        public function getLastID() : int {
            $sqlString = "select id from Student order by id desc limit 1;";
            $query = $this->conn->prepare($sqlString);
            $query->execute();
            return (int) $query->fetchColumn()+1 ?? null;
        }

        public function getNextID(): int {
            $sql = "SELECT MAX(id) AS max_id FROM Student;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $maxId = $query->fetchColumn();

            if ($maxId === false || $maxId === null) {
                // Si no hay registros, empezamos en 1
                return 1;
            }

            return ((int)$maxId) + 1;
        }

        public function addStudent($student) {
            try{
                $this->conn->beginTransaction();
                $sqlString = "insert into Student (dni, name, surname, age) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sqlString);
                $name = $student->getName();
                $surname = $student->getSurname();
                $dni = $student->getDni();
                $age = $student->getAge();
                $stmt->execute([$dni, $name, $surname, $age]);
                $this->conn->commit();
            }
            catch(PDOException $erro){
                // roll back the transaction if something failed
                $this->conn->rollback();
                throw $erro;
            }
        }

        public function deleteById($id){
            try{
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare("delete from Student where id=?");
                $stmt->execute([$id]);
                $this->conn->commit();
            }
            catch(PDOException $e) {
                // roll back the transaction if something failed
                $this->conn->rollback();
                throw $e;
            }
        }


        public function searchByValue($searchType, $searchValue){

            $validFields = ['id', 'dni', 'name', 'surname', 'age'];
            if (!in_array($searchType, $validFields)) {
                throw new Exception("Campo de búsqueda no válido.");
            }

            $sqlString = "select * from Student where $searchType like ?";

            try{
                $query = $this->conn->prepare($sqlString);

                $searchValue = "%" . $searchValue . "%"; //esto e para que devolva todas as coincidencias dese tipo
                $query->execute([$searchValue]);
    
                $list = array();
    
                while($qElement = $query->fetch()){
                    $student = new Student();
                    $student->setId($qElement['id']);
                    $student->setDni($qElement['dni'] ?? null);
                    $student->setName($qElement['name'] ?? null);
                    $student->setSurname($qElement['surname'] ?? null);
                    $student->setAge($qElement['age'] ?? null);
                    $list[] = $student;
                }
                return $list;
            }
            catch(PDOException $e) {
                // roll back the transaction if something failed
                $this->conn->rollback();
                throw $e;
            }
            
        }
        
        public function searchDefault($value){
            $sqlString = "select * from Student where id=?";

            try{
                $query = $this->conn->prepare($sqlString);
                $query->execute([$id]);
    
                $list = array();
    
                while($qElement = $query->fetch()){
                    $student = new Student();
                    $student->setId($qElement['id']);
                    $student->setDni($qElement['dni'] ?? null);
                    $student->setName($qElement['name'] ?? null);
                    $student->setSurname($qElement['surname'] ?? null);
                    $student->setAge($qElement['age'] ?? null);
                    $list[] = $student;
                }
                return $list;
            }
            catch(PDOException $e) {
                // roll back the transaction if something failed
                $this->conn->rollback();
                throw $e;
            }
            
        }


    }

?>