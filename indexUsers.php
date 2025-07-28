<?php
$sqlAllLivre = "
    SELECT 
        livres.idLivre,
        livres.nomLivre AS 'Titre :',
        livres.`annéeLivre` AS 'Année :',
        livres.disponible,
        CONCAT(ecrivains.prenomEcrivain, ' ', ecrivains.nomEcrivain) AS auteur,
        genres.libelle AS genre
        FROM livres
        INNER JOIN ecrivains ON livres.idEcrivain = ecrivains.idEcrivain
        INNER JOIN genres ON livres.idGenre = genres.idGenre
";

$stmtAllLivre = $pdo->prepare($sqlAllLivre);
$stmtAllLivre->execute();
$resultatsAllLivre = $stmtAllLivre->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'emprunt 

if (isset($_POST['emprunter'])) {
    $idLivre = $_POST['idLivre'];
    $idUtilisateur = $_SESSION['utilisateurs']['idUtilisateur'];
    $dateEmprunt = date('Y-m-d');

    $sqlEmprunt = "INSERT INTO emprunts (idUtilisateur, idLivre, dateEmprunt, dateRetour) VALUES (?, ?, ?, NULL)";
    $stmtEmprunt = $pdo->prepare($sqlEmprunt);
    $stmtEmprunt->execute([$idUtilisateur, $idLivre, $dateEmprunt]);

    // Livre non disponible
    $sqlUpdate = "UPDATE livres SET disponible = 0 WHERE idLivre = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([$idLivre]);

    echo "Livre emprunté <br><br>";
}

// Afficher les livres empruntés 
echo "<h3>Mes livres empruntés</h3>";

$idUtilisateur = $_SESSION['utilisateurs']['idUtilisateur'];
$sqlMesEmprunts = "
    SELECT 
        emprunts.idEmprunt,
        emprunts.dateEmprunt,
        livres.idLivre,
        livres.nomLivre,
        CONCAT(ecrivains.prenomEcrivain, ' ', ecrivains.nomEcrivain) AS auteur
    FROM emprunts
    INNER JOIN livres ON emprunts.idLivre = livres.idLivre
    INNER JOIN ecrivains ON livres.idEcrivain = ecrivains.idEcrivain
    WHERE emprunts.idUtilisateur = ? AND emprunts.rendu = 0
";

$stmtMesEmprunts = $pdo->prepare($sqlMesEmprunts);
$stmtMesEmprunts->execute([$idUtilisateur]);
$resultsMesEmprunts = $stmtMesEmprunts->fetchAll(PDO::FETCH_ASSOC);

// Traitement du retour de livre
if (isset($_POST['rendre'])) {
    $idEmprunt = $_POST['idEmprunt'];
    $idLivre = $_POST['idLivre'];
    $dateRetour = date('Y-m-d');

    // Mettre à jour l'emprunt (marquer comme rendu et ajouter la date de retour)
    $sqlRetour = "UPDATE emprunts SET rendu = 1, dateRetour = ? WHERE idEmprunt = ?";
    $stmtRetour = $pdo->prepare($sqlRetour);
    $stmtRetour->execute([$dateRetour, $idEmprunt]);

    // Remettre le livre comme disponible
    $sqlUpdateLivre = "UPDATE livres SET disponible = 1 WHERE idLivre = ?";
    $stmtUpdateLivre = $pdo->prepare($sqlUpdateLivre);
    $stmtUpdateLivre->execute([$idLivre]);

    echo "Livre rendu <br><br>";
}

if (!empty($resultsMesEmprunts)) {
    foreach ($resultsMesEmprunts as $emprunt) {
        echo "Livre : " . htmlspecialchars($emprunt['nomLivre']) . "<br>";
        echo "Auteur : " . htmlspecialchars($emprunt['auteur']) . "<br>";
        echo "Emprunté le : " . htmlspecialchars($emprunt['dateEmprunt']) . "<br>";

        echo "<form method='POST' style='display: inline;'>
                <input type='hidden' name='idEmprunt' value='" . $emprunt['idEmprunt'] . "'>
                <input type='hidden' name='idLivre' value='" . $emprunt['idLivre'] . "'>
                <input type='submit' name='rendre' value='Rendre ce livre'>
              </form>";
        echo "<hr>";
    }
} else {
    echo "Vous n'avez aucun livre emprunté.<br>";
}

echo "<h3>Tous les livres disponibles</h3>";

foreach ($resultatsAllLivre as $key => $value) {

    echo "Titre : " . htmlspecialchars($value['Titre :']) . "<br>";
    echo " Année : " . htmlspecialchars($value['Année :']) . "<br>";
    echo " Auteur : " . htmlspecialchars($value['auteur']) . "<br>";
    echo " Genre : " . htmlspecialchars($value['genre']) . "<br>";
    echo "<br>";

    // BOUTON D'EMPRUNT
    if ($value['disponible'] == 1) {
        echo "Disponible<br>";
        echo "<form method='POST'>
                <input type='hidden' name='idLivre' value='" . $value['idLivre'] . "'>
                <input type='submit' name='emprunter' value='Emprunter'>
              </form>";
    } else {
        echo "Non disponible<br>";
    }

    echo "<hr>";
}
