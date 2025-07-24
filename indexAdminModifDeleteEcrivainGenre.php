<?php

$sqlAllEcrivain = "SELECT * FROM `ecrivains`";
$stmtAllEcrivain = $pdo->prepare($sqlAllEcrivain);
$stmtAllEcrivain->execute();
$resultsAllEcrivain = $stmtAllEcrivain->fetchAll(PDO::FETCH_ASSOC);


// MODIFICATION DES ECRIVAINS EN BDD
foreach ($resultsAllEcrivain as $key => $value) {

    $idModifierEcrivain = $value['idEcrivain'];

    echo "<form method='POST'>";
    echo "<input type='hidden' name='idDelete' value='$idModifierEcrivain'>";

    foreach ($value as $key => $value2) {
        echo htmlspecialchars($key) . " : " . htmlspecialchars($value2);
        echo "<br>";
    }

    echo '<a href="?page=viewModifEcrivainGenre&id=' . $idModifierEcrivain . '">Modifier</a>';
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
//  FORMULAIRE DE MODIF USERS
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
        <label>Email</label>
        <input type="text" name="nationalitéEcrivain" value="' . htmlspecialchars($resultsId[0]['nationalitéEcrivain']) . '">
        <br>
        <input type="submit" name="envoiEcrivainUpdate" value="Mettre à jour les données">
    </form>';
}

if (isset($_POST['envoiEcrivainUpdate'])) {

    $idEcrivain = $_POST['idEcrivain'];
    $nomEcrivain = $_POST['nomEcrivain'];
    $prenomEcrivain = $_POST['prenomEcrivain'];
    $nationalitéEcrivain = $_POST['nationalitéEcrivain'];


    $sqlUpdate = "UPDATE `ecrivains` SET `nomEcrivain`= :nomEcrivain, `prenomEcrivain`= :prenomEcrivain, `nationalitéEcrivain`= :nationalitéEcrivain WHERE idEcrivain= :idEcrivain";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $stmtUpdate->bindParam(':nomEcrivain', $nomEcrivain);
    $stmtUpdate->bindParam(':prenomEcrivain', $prenomEcrivain);
    $stmtUpdate->bindParam(':nationalitéEcrivain', $nationalitéEcrivain);
   
    $stmtUpdate->bindParam(':idEcrivain', $idEcrivain);

    $stmtUpdate->execute();

    header("Location: indexLogin.php?page=viewModifEcrivainGenre");
}


?>