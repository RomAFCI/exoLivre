<?php
$sqlEcrivain = "SELECT * FROM `ecrivains`";
$stmtEcrivain = $pdo->prepare($sqlEcrivain);
$stmtEcrivain->execute();
$resultEcrivain = $stmtEcrivain->fetchAll(PDO::FETCH_ASSOC);

$sqlGenre = "SELECT * FROM `genres`";
$stmtGenre = $pdo->prepare($sqlGenre);
$stmtGenre->execute();
$resultGenre = $stmtGenre->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <hr>
    <h2>Ajout</h2>
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
            <br>
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
    // FIN DE L'ENVOI POUR L'ECRIVAIN
    ?>

    <h3>Ajout de genre littéraire</h3>
    <a href="?page=AddGenre">
        <p>Ajoutez un genre</p>
    </a>
    <?php

    // ENVOI DE DONNEES EN BDD POUR LE GENRE
    if (isset($_GET['page']) && $_GET['page'] == 'AddGenre') {

        echo "<form method='POST'>
            <label>Ajout nom de genre</label>
            <input type='text' name='nomGenre' required>
            <br>
            <input type='submit' name='createGenre' value='Ajoutez'>
            <br>
        </form>";
    }

    if (isset($_POST['createGenre'])) {
        $nomGenre = $_POST['nomGenre'];

        $sqlAddGenre = "INSERT INTO `genres`(`libelle`) VALUES (?)";
        $stmtAddGenre = $pdo->prepare($sqlAddGenre);

        $stmtAddGenre->execute([$nomGenre]);
    }
    // FIN DE L'ENVOI POUR LE GENRE
    ?>

    <hr>
    <h3>Ajout de Livre</h3>
    <a href="?page=AddLivre">
        <p>Ajoutez un livre</p>
    </a>
    <?php

    // ENVOI DE DONNEES EN BDD POUR LE LIVRE
    // ENVOI DU NOM DE L'ANNEE DE L'ECRIVAIN DU GENRE ET DISPONIBILITE
    if (isset($_GET['page']) && $_GET['page'] == 'AddLivre') {

        echo '<form method="POST">
        <label>Titre du livre</label>
        <input type="text" name="nomLivre" required>
        <br>
        <label>Année du livre</label>
        <input type="text" name="anneeLivre" required>
        <br>
        <label>Sélectionnez un(e) écrivain(e) </label>
        <select name="selectEcrivain">';

        foreach ($resultEcrivain as $key => $value) {
            // echo "<option value='" . $value["idEcrivain"] . "'>" . $value['nomEcrivain'] . $value['prenomEcrivain'] . $value['nationalitéEcrivain'] . "</option>";
            // correction pour lisibilité
            echo "<option value='" . $value["idEcrivain"] . "'>" . $value['nomEcrivain'] . " " . $value['prenomEcrivain'] . " (" . $value['nationalitéEcrivain'] . ")</option>";
        }
        echo '</select>
        <br>
        <label>Sélectionnez un genre</label>
        <select name="selectGenre">';

        foreach ($resultGenre as $key => $value) {
            echo "<option value='" . $value["idGenre"] . "'>" . $value['libelle'] . "</option>";
        }
        echo '</select>
        <br>
        <label>Disponible</label>
        <input type="checkbox" name="checkLivre" />
        <br>
        <input type="submit" name="createLivre" value="Ajoutez le livre">

    </form>';
    }

    if (isset($_POST['createLivre'])) {
        $nomLivre = $_POST['nomLivre'];
        $anneeLivre = $_POST['anneeLivre'];
        $selectEcrivain = $_POST['selectEcrivain'];
        $selectGenre = $_POST['selectGenre'];

        // $checkLivre = $_POST["checkLivre"];
        // correction gpt pour le boolean de la checkbox
        // apparament possible en ternaire
        // Gestion de la checkbox en if/else
        if (isset($_POST["checkLivre"])) {
            $checkLivre = 1;
        } else {
            $checkLivre = 0;
        }

        $sqlAddLivre = "INSERT INTO `livres`(`nomLivre`, `annéeLivre`, `disponible`, `idEcrivain`, `idGenre`) VALUES (:nomLivre, :anneeLivre, :checkLivre, :idEcrivain, :idGenre)";
        $stmtAddLivre = $pdo->prepare($sqlAddLivre);


        // BIN PARAM POUR GUILLEMET ET SECURISER INJECTION SQL
        $stmtAddLivre->bindParam(':nomLivre', $nomLivre);
        $stmtAddLivre->bindParam(':anneeLivre', $anneeLivre);
        $stmtAddLivre->bindParam(':checkLivre', $checkLivre);
        $stmtAddLivre->bindParam(':idEcrivain', $selectEcrivain);
        $stmtAddLivre->bindParam(':idGenre', $selectGenre);

        $stmtAddLivre->execute();
    }
    // FIN DE L'ENVOI POUR LE LIVRE
    ?>

    <hr>
    <h2>Modification</h2>
    <h3>Modifcation des écrivains et des genres</h3>
    <a href="?page=viewModifEcrivainGenre">
        <p>Afficher les modifications</p>
    </a>
    <?php
    if (isset($_GET['page']) && $_GET['page'] == 'viewModifEcrivainGenre') {
        include 'indexAdminModifDeleteEcrivainGenre.php';
    }
    ?>

    <hr>
    <h3>Modifcation des livres</h3>
    <a href="?page=viewModifBook">
        <p>Afficher les modifications</p>
    </a>
    <?php
    if (isset($_GET['page']) && $_GET['page'] == 'viewModifBook') {
        include 'indexAdminModifDeleteBook.php';
    }
    ?>

    <hr>
    <h3>Modifcation des utilisateurs</h3>
    <a href="?page=viewModifUser">
        <p>Afficher les modifications</p>
    </a>
    <?php
    if (isset($_GET['page']) && $_GET['page'] == 'viewModifUser') {
        include 'indexAdminModifDeleteUser.php';
    }
    ?>

