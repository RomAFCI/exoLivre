<?php
$sqlAllLivre = "
    SELECT 
        livres.nomLivre AS 'Titre :',
        livres.`annéeLivre` AS 'Année :',
        CONCAT(ecrivains.prenomEcrivain, ' ', ecrivains.nomEcrivain) AS auteur,
        genres.libelle AS genre
        FROM livres
        INNER JOIN ecrivains ON livres.idEcrivain = ecrivains.idEcrivain
        INNER JOIN genres ON livres.idGenre = genres.idGenre
";

$stmtAllLivre = $pdo->prepare($sqlAllLivre);
$stmtAllLivre->execute();
$resultatsAllLivre = $stmtAllLivre->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultatsAllLivre as $key => $value) {

    echo "Titre : " . htmlspecialchars($value['Titre :']) . "<br>";
    echo " Année : " . htmlspecialchars($value['Année :']) . "<br>";
    echo " Auteur : " . htmlspecialchars($value['auteur']) . "<br>";
    echo " Genre : " . htmlspecialchars($value['genre']) . "<br>";
    echo "<hr>";
}


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
    $dateRetour = date('Y-m-d', strtotime('+14 days')); // 14 jours d'emprunt

    // Insérer l'emprunt
    $sqlEmprunt = "INSERT INTO emprunts (idUtilisateur, idLivre, dateEmprunt, dateRetour) VALUES (?, ?, ?, ?)";
    $stmtEmprunt = $pdo->prepare($sqlEmprunt);
    $stmtEmprunt->execute([$idUtilisateur, $idLivre, $dateEmprunt, $dateRetour]);

    // Mettre le livre comme non disponible
    $sqlUpdate = "UPDATE livres SET disponible = 0 WHERE idLivre = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([$idLivre]);

    echo "Livre emprunté avec succès ! Date de retour : " . $dateRetour . "<br><br>";
}

foreach ($resultatsAllLivre as $key => $value) {
    echo "Titre : " . htmlspecialchars($value['Titre :']) . "<br>";
    echo "Année : " . htmlspecialchars($value['Année :']) . "<br>";
    echo "Auteur : " . htmlspecialchars($value['auteur']) . "<br>";
    echo "Genre : " . htmlspecialchars($value['genre']) . "<br>";
    
    // Afficher le statut et le bouton d'emprunt
    if ($value['disponible'] == 1) {
        echo "Statut : <span style='color: green;'>Disponible</span><br>";
        echo "<form method='POST' style='display: inline;'>
                <input type='hidden' name='idLivre' value='" . $value['idLivre'] . "'>
                <input type='submit' name='emprunter' value='Emprunter ce livre'>
              </form>";
    } else {
        echo "Statut : <span style='color: red;'>Non disponible</span><br>";
    }
    
    echo "<hr>";
}
?>