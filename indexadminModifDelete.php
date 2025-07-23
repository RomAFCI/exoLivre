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
    echo '<input type="submit" name="supprimer" value="Supprimer"><br>';
    echo "<br>";
    echo "<br>";
}
//SUPPRIMER LIVRE EN BDD
if (isset($_POST['supprimer'])) {
    $idToDelete = $_POST['idDelete'];
    $sqlDelete = "DELETE FROM `livres` WHERE idLivre = '$idLivre'";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute();
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

    $sqlEcrivain = "SELECT * FROM `ecrivains`";
    $stmtEcrivain = $pdo->prepare($sqlEcrivain);
    $stmtEcrivain->execute();
    $resultEcrivain = $stmtEcrivain->fetchAll(PDO::FETCH_ASSOC);

    $sqlGenre = "SELECT * FROM `genres`";
    $stmtGenre = $pdo->prepare($sqlGenre);
    $stmtGenre->execute();
    $resultGenre = $stmtGenre->fetchAll(PDO::FETCH_ASSOC);

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
        <label>Modifier un écrivain</label>
        <select name="updateEcrivain">';

    foreach ($resultEcrivain as $key => $value) {
        echo "<option value='" . $value["idEcrivain"] . "'>" . $value['nomEcrivain'] . " " . $value['prenomEcrivain'] . " (" . $value['nationalitéEcrivain'] . ")</option>";
    }
    echo '</select>
        <br>
        <label>Modifier un genre</label>
        <select name="updateGenre">';

    foreach ($resultGenre as $key => $value) {
        echo "<option value='" . $value["idGenre"] . "'>" . $value['libelle'] . "</option>";
    }
    echo '</select>
        <br>
        <input type="submit" name="envoiLivreUpdate" value="Mettre à jour les données">
    </form>';
}

if (isset($_POST['envoiLivreUpdate'])) {

    $idLivre = $_POST['idLivre'];
    $updateNomLivre = $_POST['updateNomLivre'];
    $updateDate = $_POST['updateDate'];
    if (isset($_POST["updateDispo"])) {
        $updateDispo = 1;
    } else {
        $updateDispo = 0;
    }
    $updateEcrivain = $_POST['updateEcrivain'];
    $updateGenre = $_POST['updateGenre'];



    $sqlUpdate = "UPDATE `livres` SET `nomLivre`= :updateNomLivre, `annéeLivre`= :updateDate, `disponible`= :updateDispo, `idEcrivain`= :updateEcrivain, `idGenre`= :updateGenre
    WHERE idLivre= :idLivre";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->bindParam(':updateNomLivre', $updateNomLivre);
    $stmtUpdate->bindParam(':updateDate', $updateDate);
    $stmtUpdate->bindParam(':updateDispo', $updateDispo);
    $stmtUpdate->bindParam(':updateEcrivain', $updateEcrivain);
    $stmtUpdate->bindParam(':updateGenre', $updateGenre);
    $stmtUpdate->bindParam(':idLivre', $idLivre);

    $stmtUpdate->execute();

    header("Location: index.php?page=viewModif");
}

?>