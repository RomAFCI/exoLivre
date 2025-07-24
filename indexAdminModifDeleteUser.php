<?php

$sqlAllUser = "SELECT * FROM `utilisateurs`";
$stmtAllUser = $pdo->prepare($sqlAllUser);
$stmtAllUser->execute();
$resultsAllUser = $stmtAllUser->fetchAll(PDO::FETCH_ASSOC);


// MODIFICATION DES USERS EN BDD
foreach ($resultsAllUser as $key => $value) {

    $idModifier = $value['idUtilisateur'];

    echo "<form method='POST'>";
    echo "<input type='hidden' name='idDelete' value='$idModifier'>";

    foreach ($value as $key => $value2) {
        echo htmlspecialchars($key) . " : " . htmlspecialchars($value2);
        echo "<br>";
    }

    echo '<a href="?page=viewModifUser&id=' . $idModifier . '">Modifier</a>';
    echo '<input type="submit" name="supprimer" value="Supprimer"><br>';
    echo "</form>";
    echo "<br>";
    echo "<br>";
}
//SUPPRIMER USERS EN BDD
if (isset($_POST['supprimer'])) {
    $idToDelete = $_POST['idDelete'];
    $sqlDelete = "DELETE FROM `utilisateurs` WHERE idUtilisateur = '$idToDelete'";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute();
}
?>

<?php
//  FORMULAIRE DE MODIF USERS
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlId = "SELECT * FROM `Utilisateurs` WHERE `idUtilisateur` = '$id'";
    $stmtId = $pdo->prepare($sqlId);
    $stmtId->execute();
    $resultsId = $stmtId->fetchAll(PDO::FETCH_ASSOC);


    echo '<form method="POST">
        <input type="hidden" name="idUtilisateur" value="' . htmlspecialchars($resultsId[0]['idUtilisateur']) . '">
        <br>
        <label>Nom</label>
        <input type="text" name="nomUtilisateur" value="' . htmlspecialchars($resultsId[0]['nomUtilisateur']) . '">
        <br>
        <label>Prénom</label>
        <input type="text" name="prenomUtilisateur" value="' . htmlspecialchars($resultsId[0]['prenomUtilisateur']) . '">
        <br>
        <label>Email</label>
        <input type="text" name="emailUtilisateur" value="' . htmlspecialchars($resultsId[0]['emailUtilisateur']) . '">
        <br>
        <input type="submit" name="envoiUserUpdate" value="Mettre à jour les données">
    </form>';
}

if (isset($_POST['envoiUserUpdate'])) {

    $idUtilisateur = $_POST['idUtilisateur'];
    $nomUtilisateur = $_POST['nomUtilisateur'];
    $prenomUtilisateur = $_POST['prenomUtilisateur'];
    $emailUtilisateur = $_POST['emailUtilisateur'];
    

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