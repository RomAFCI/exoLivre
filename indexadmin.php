<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h3>Ajout d'écrivain</h3>
    <a href="?page=AddEcrivain">
        <p>Ajoutez un écrivain</p>
    </a>
    <?php

    // ENVOI DE DONNEES EN BDD POUR L'ECRIVAIN
    // ENVOI DU NOM DU PRENOM ET DE LA NATIONALITE
    if (isset($_GET['page']) && $_GET['page'] == 'AddEcrivain') {

        echo "<form method='POST'>
            <label>Ajout nom d'écrivain</label>
            <input type='text' name='nomEcrivain' required>
            <br>
            <label>Ajout prénom d'écrivain</label>
            <input type='text' name='prenomEcrivain' required>
            <br>
            <label>Ajout nationalité d'un écrivain</label>
            <input type='text' name='nationaliteEcrivain' required>
            <br>
            <input type='submit' name='createEcrivain' value='Ajoutez'>
        </form>";
    }

    if (isset($_POST['createEcrivain'])) {
        $nomEcrivain = $_POST['nomEcrivain'];
        $prenomEcrivain = $_POST['prenomEcrivain'];
        $nationaliteEcrivain = $_POST['nationaliteEcrivain'];


        $sqlAddEcrivain = "INSERT INTO `ecrivains`(`nomEcrivain`, `prenomEcrivain`, `nationalitéEcrivain`) 
            VALUES (?,?,?)";
        $stmtAddEcrivain = $pdo->prepare($sqlAddEcrivain);

        $stmtAddEcrivain->execute([$nomEcrivain, $prenomEcrivain, $nationaliteEcrivain]);
    }

    ?>

</body>

</html>
















<!-- $sqlEcrivain = "SELECT * FROM `ecrivains`";
     $stmtEcrivain = $pdo->prepare($sqlEcrivain);
     $stmtEcrivain->execute();
     $resultEcrivain = $stmtEcrivain->fetchAll(PDO::FETCH_ASSOC); -->