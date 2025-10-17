<?php
    $nameErr = $surnameErr = $dniErr = $ageErr = "";
    $name = $surname = $dni = $age = "";

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    require_once "conOp/Operations.php";
    try {
        $oper = new Operations();
        $oper->openConnection();
        //echo "Connection created";
        $students = $oper->getAllStudents();
        $lastID = $oper->getLastID();
        $nextID = $oper->getNextID();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update'])) {
                $id = (int)$_POST['update'];
                // Lógica para actualizar estudiante con $id
            } elseif (isset($_POST['delete'])) {
                $id = (int)$_POST['delete'];
                $oper->deleteById($id);

                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
            
            $hasError = false;
            
            if (isset($_POST['add'])) {
                if (empty($_POST["name"])) {
                    $nameErr = "Name is required";
                    $hasError = true;

                } else {
                    $name = test_input($_POST["name"]);
                }

                if (empty($_POST["surname"])) {
                    $surnameErr = "Surname is required";
                    $hasError = true;
                } else {
                    $surname = test_input($_POST["surname"]);
                }

                if (empty($_POST["dni"])) {
                    $dniErr = "DNI is required";
                    $hasError = true;
                } else {
                    $dni = test_input($_POST["dni"]);
                }

                if (empty($_POST["age"])) {
                    $ageErr = "Age is required";
                    $hasError = true;
                } else {
                    $age = test_input($_POST["age"]);
                }

                if(!$hasError){
                    $student = new Student();
                    $student->setDni($dni);
                    $student->setName($name);
                    $student->setSurname($surname);
                    $student->setAge($age);
                    $oper->addStudent($student);

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }
                else{
                    echo "<p style='color:red'>Errores:</p>";
                    echo $nameErr ? "<p>$nameErr</p>" : "";
                    echo $surnameErr ? "<p>$surnameErr</p>" : "";
                    echo $dniErr ? "<p>$dniErr</p>" : "";
                    echo $ageErr ? "<p>$ageErr</p>" : "";
                }
            }
        }
    } catch (PDOException $e) {
        echo "<br><p style='color:red;'>".$e->getMessage()."<p>";
    } catch (Exception $e){
        echo "<br><p style='color:red;'>".$e->getMessage()."<p>";
    } finally {
        $oper->closeConnection();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./static/style.css">
</head>
<script src="static/script.js" defer></script>

<body>
    <h2>Lista alumnos</h2>
    

    <div id="tabla">
        <div class="search">
            <form id="searchForm" method="post">
                <select id="searchType" name="searchType">
                    <option value="id">ID</option>
                    <option value="dni">DNI</option>
                    <option value="name">Name</option>
                    <option value="surname">Surname</option>
                    <option value="age">Age</option>
                </select>
                <label for="searchInput" style="display: flex;">
                    <input id="searchInput" name="searchValue" type="number" placeholder="Enter a number" >
                </label>
            </form>
            <div class="buttonSearch">
                <button class="bUpDel">Search</button>
            </div>
        </div>
        <br><br>
        <?php foreach ($students as $student): ?>
            <div class="row" id="<?=$student->getId()?>">
                <div class="rowObject" >
                    <p><?= $student->getId() ?></p>
                    <p><?= $student->getDni() ?></p>
                    <p><?= $student->getName()?></p>
                    <p><?= $student->getSurname() ?></p>
                    <p><?= $student->getAge() ?></p>
                </div>
                <form class="buttons" method="post" action="">
                    <button class="bUpDel" name="update" type="submit" value="<?=$student->getId()?>">Update</button>
                    <button class="bUpDel" name="delete" type="submit" value="<?=$student->getId()?>">Delete</button>
                </form>
                
            </div>
        <?php endforeach; ?>
        <div class="add">
            <form id="addForm" method="post">
                <input id="Id" name="id" type="text" value="<?=$nextID?>" disabled>
                <input type="text" name="dni" placeholder="12345678A">
                <input type="text" name="name" placeholder="Pepito">
                <input type="text" name="surname" placeholder="Gomez">
                <input name="age" style="padding-left: 15px;" type="number" min="5" max="50" placeholder="5">
                <button class="buttonAdd" name="add" type="submit" class="bUpDel">add</button>
            </form>
        </div>
    </div>

</body>
</html>

