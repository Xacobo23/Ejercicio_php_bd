<?php
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
</style>
<body>
    <h2>Lista alumnos</h2>
    

    <div id="tabla">
        <div class="search">
            <form id="searchForm" method="post">
                <label for="id">
                    ID:<input id="Id" name="id" type="number" >
                </label>
                
                <input type="text">
                <input type="text">
                <input type="text">
                <input style="padding-left: 15px;" type="number" min="5" max="50" placeholder="5">
            </form>
            <div class="buttonSearch">
                <button class="bUpDel">add</button>
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
                <input type="text" placeholder="12345678A">
                <input type="text" placeholder="Pepito">
                <input type="text" placeholder="Gomez">
                <input style="padding-left: 15px;" type="number" min="5" max="50" placeholder="5">
            </form>
            <div class="buttonAdd">
                <button class="bUpDel">add</button>
            </div>
        </div>
    </div>

</body>
</html>