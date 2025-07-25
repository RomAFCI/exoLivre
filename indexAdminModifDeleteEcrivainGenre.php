<?php

$sqlAllEcrivain = "SELECT * FROM `ecrivains`";
$stmtAllEcrivain = $pdo->prepare($sqlAllEcrivain);
$stmtAllEcrivain->execute();
$resultsAllEcrivain = $stmtAllEcrivain->fetchAll(PDO::FETCH_ASSOC);


// MODIFICATION DES ECRIVAINS EN BDD
foreach ($resultsAllEcrivain as $key => $value) {

    $idModifier = $value['idEcrivain'];

    echo "<form method='POST'>";
    echo "<input type='hidden' name='idDelete' value='$idModifier'>";

    foreach ($value as $key => $value2) {
        echo htmlspecialchars($key) . " : " . htmlspecialchars($value2);
        echo "<br>";
    }

    echo '<a href="?page=viewModifEcrivainGenre&id=' . $idModifier . '">Modifier</a>';
    echo '<input type="submit" name="supprimer" value="Supprimer"><br>';
    echo "</form>";
    echo "<br>";
    echo "<br>";
}
//SUPPRIMER ECRIVAIN EN BDD
if (isset($_POST['supprimer'])) {
    $idToDelete = $_POST['idDelete'];
    $sqlDelete = "DELETE FROM `ecrivains` WHERE idEcrivain = '$idToDelete'";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute();
    header("Location: indexLogin.php?page=viewModifEcrivainGenre");
}
?>

<?php
//  FORMULAIRE DE MODIF ECRIVAIN
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlId = "SELECT * FROM `ecrivains` WHERE `idEcrivain` = '$id'";
    $stmtId = $pdo->prepare($sqlId);
    $stmtId->execute();
    $resultsId = $stmtId->fetchAll(PDO::FETCH_ASSOC);


    echo '<form method="POST">
        <input type="hidden" name="idEcrivain" value="' . htmlspecialchars($resultsId[0]['idEcrivain']) . '">
        <br>
        <label>Nom</label>
        <input type="text" name="nomEcrivain" value="' . htmlspecialchars($resultsId[0]['nomEcrivain']) . '">
        <br>
        <label>Prénom</label>
        <input type="text" name="prenomEcrivain" value="' . htmlspecialchars($resultsId[0]['prenomEcrivain']) . '">
        <br>
        <label>Nationalité</label>
        <input type="text" name="nationaliteEcrivain" value="' . htmlspecialchars($resultsId[0]['nationalitéEcrivain']) . '">
        <br>
        <input type="submit" name="envoiEcrivainUpdate" value="Mettre à jour les données">
    </form>';
}

if (isset($_POST['envoiEcrivainUpdate'])) {

    $idEcrivain = $_POST['idEcrivain'];
    $nomEcrivain = $_POST['nomEcrivain'];
    $prenomEcrivain = $_POST['prenomEcrivain'];
    $nationaliteEcrivain = $_POST['nationalitéEcrivain'];

    $sqlUpdate = "UPDATE `ecrivains` SET `nomEcrivain`= :nomEcrivain, `prenomEcrivain`= :prenomEcrivain, `nationalitéEcrivain`= :nationaliteEcrivain WHERE idEcrivain= :idEcrivain";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->bindParam(':nomEcrivain', $nomEcrivain);
    $stmtUpdate->bindParam(':prenomEcrivain', $prenomEcrivain);
    $stmtUpdate->bindParam(':nationaliteEcrivain', $nationaliteEcrivain);
    $stmtUpdate->bindParam(':idEcrivain', $idEcrivain);

    $stmtUpdate->execute();

    header("Location: indexLogin.php?page=viewModifEcrivainGenre");
}


?>

<?php

$sqlAllGenre = "SELECT * FROM `genres`";
$stmtAllGenre = $pdo->prepare($sqlAllGenre);
$stmtAllGenre->execute();
$resultsAllGenre = $stmtAllGenre->fetchAll(PDO::FETCH_ASSOC);


// MODIFICATION DES GENRES EN BDD
foreach ($resultsAllGenre as $key => $value) {

    $idModifGenre = $value['idGenre'];

    echo "<form method='POST'>";
    echo "<input type='hidden' name='idDelete' value='$idModifGenre'>";

    foreach ($value as $key => $value2) {
        echo htmlspecialchars($key) . " : " . htmlspecialchars($value2);
        echo "<br>";
    }

    echo '<a href="?page=viewModifEcrivainGenre&id=' . $idModifGenre . '">Modifier</a>';
    echo '<input type="submit" name="supprimer" value="Supprimer"><br>';
    echo "</form>";
    echo "<br>";
    echo "<br>";
}
//SUPPRIMER GENRE EN BDD
if (isset($_POST['supprimer'])) {
    $idToDeleteGenre = $_POST['idDelete'];
    $sqlDeleteGenre = "DELETE FROM `genres` WHERE idGenre = '$idToDeleteGenre'";
    $stmtDeleteGenre = $pdo->prepare($sqlDeleteGenre);
    $stmtDeleteGenre->execute();
    header("Location: indexLogin.php?page=viewModifEcrivainGenre");
}
?>

<?php
//  FORMULAIRE DE MODIF GENRE
if (isset($_GET['id'])) {
    $idGenre = $_GET['id'];
    $sqlId = "SELECT * FROM `genres` WHERE `idGenre` = '$idGenre'";
    $stmtId = $pdo->prepare($sqlId);
    $stmtId->execute();
    $resultsIdGenre = $stmtId->fetchAll(PDO::FETCH_ASSOC);

    echo '<form method="POST">
        <input type="hidden" name="idGenre" value="' . htmlspecialchars($resultsIdGenre[0]['idGenre']) . '">
        <br>
        <label>Genre</label>
        <input type="text" name="libelle" value="' . htmlspecialchars($resultsIdGenre[0]['libelle']) . '">
        <br>
        <input type="submit" name="envoiGenreUpdate" value="Mettre à jour les données">
    </form>';
}

if (isset($_POST['envoiGenreUpdate'])) {

    $idGenre = $_POST['idGenre'];
    $libelle = $_POST['libelle'];

    $sqlUpdate = "UPDATE `genres` SET `libelle`= :libelle WHERE idGenre= :idGenre";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->bindParam(':libelle', $libelle);
    $stmtUpdate->bindParam(':idGenre', $idGenre);

    $stmtUpdate->execute();

    header("Location: indexLogin.php?page=viewModifEcrivainGenre");
}

?>