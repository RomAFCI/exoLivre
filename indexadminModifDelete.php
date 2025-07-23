<?php

$sqlAllLivre = "SELECT * FROM `livres`";
$stmtAllLivre = $pdo->prepare($sqlAllLivre);
$stmtAllLivre->execute();
$resultsAllLivre = $stmtAllLivre->fetchAll(PDO::FETCH_ASSOC);


// $sqlEcrivain = "SELECT * FROM `ecrivains`";
//      $stmtEcrivain = $pdo->prepare($sqlEcrivain);
//      $stmtEcrivain->execute();
//      $resultEcrivain = $stmtEcrivain->fetchAll(PDO::FETCH_ASSOC);


// MODIFICATION DES LIVRES EN BDD
foreach ($resultsAllLivre as $key => $value) {
    $idModifier = $value['idLivre'];
    foreach ($value as $key => $value2) {
        echo htmlspecialchars($key) . " : " . htmlspecialchars($value2);
        echo "<br>";
    }
    echo '<a href="?page=viewModif&id=' . $idModifier . '">Modifier</a>';
    echo "<br>";
    echo "<br>";
}
?>

<hr>

<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlId = "SELECT * FROM `livres` WHERE `idLivre` = '$id'";
    $stmtId = $pdo->prepare($sqlId);
    $stmtId->execute();
    $resultsId = $stmtId->fetchAll(PDO::FETCH_ASSOC);

    // POUR LE SELECT OPTION LES ECRIVAINS ET LES GENRES
    $sqlEcrivains = "SELECT nomEcrivain FROM ecrivains";
    $stmtEcrivains = $pdo->prepare($sqlEcrivains);
    $stmtEcrivains->execute();
    $ecrivains = $stmtEcrivains->fetchAll(PDO::FETCH_ASSOC);

    $sqlGenres = "SELECT nomGenre FROM genres";
    $stmtGenres = $pdo->prepare($sqlGenres);
    $stmtGenres->execute();
    $genres = $stmtGenres->fetchAll(PDO::FETCH_ASSOC);

    echo '<form method="POST">
        <input type="hidden" name="idLivre" value="' . htmlspecialchars($resultsId[0]['idLivre']) . '">
        <br>
        <label>Titre du livre</label>
        <input type="text" name="updateNomLivre" value="' . htmlspecialchars($resultsId[0]['nomLivre']) . '">
        <br>
        <label>Année de parution</label>
        <input type="text" name="updateDate" value="' . htmlspecialchars($resultsId[0]['annéeLivre']) . '">
        <br>
        <label>Disponible</label>
        <input type="checkbox" name="updateDispo" value="1">
        <br>
        <label>Ecrivain</label>
        <input type="text" name="updateidEcrivain" value="' . htmlspecialchars($resultsId[0]['idEcrivain']) . '">
        <br>
        <label>Libelle</label>
        <input type="text" name="updateidGenre" value="' . htmlspecialchars($resultsId[0]['idGenre']) . '">
        <br>
        <input type="submit" name="envoiLivreUpdate" value="Mettre à jour les données">
    </form>';
}

// if (isset($_POST['submitUpdate'])) {

//     $idUpdate = $_POST['idUpdate'];
//     $nom = $_POST['updateNom'];
//     $prenom = $_POST['updatePrenom'];
//     $age = $_POST['updateAge'];
//     $adresseMail = $_POST['updateAdresseMail'];
//     $password = $_POST['updatePassword'];

//     $hashPassword = password_hash('$password', PASSWORD_DEFAULT);

//     $sqlUpdate = "UPDATE `users` SET `nomUser`='$nom',`prenomUser`='$prenom',`ageUser`='$age',`adresseMailUser`='$adresseMail',`passwordUser`='$hashPassword' WHERE idUser='$idUpdate'";
//     $stmtUpdate = $pdo->prepare($sqlUpdate);
//     $stmtUpdate->execute();

//     header("Location: indexadminModifDelete.php");
// }
?>