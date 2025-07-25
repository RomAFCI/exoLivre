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





foreach ($resultatsAllLivre as $key => $value) {

    echo "Titre : " . htmlspecialchars($value['Titre :']) . "<br>";
    echo " Année : " . htmlspecialchars($value['Année :']) . "<br>";
    echo " Auteur : " . htmlspecialchars($value['auteur']) . "<br>";
    echo " Genre : " . htmlspecialchars($value['genre']) . "<br>";
    



}

