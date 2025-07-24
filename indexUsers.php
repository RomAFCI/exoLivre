<?php
$sqlAllLivre = "
    SELECT 
        livres.nomLivre AS 'Titre :,
        livres.`annéeLivre` AS 'Année :',
        ecrivains.prenomEcrivain,
        ecrivains.nomEcrivain,
        genres.libelle AS genre
    FROM livres
    INNER JOIN ecrivains ON livres.idEcrivain = ecrivains.idEcrivain
    INNER JOIN genres ON livres.idGenre = genres.idGenre
";

$stmtAllLivre = $pdo->prepare($sqlAllLivre);
$stmtAllLivre->execute();
$resultatsAllLivre = $stmtAllLivre->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultatsAllLivre as $key => $value) {
    
    echo htmlspecialchars($livre['nomLivre']) . "<br>";
    echo htmlspecialchars($livre['annéeLivre']) . "<br>";
    echo htmlspecialchars($livre['prenomEcrivain']) . " " . htmlspecialchars($livre['nomEcrivain']) . "<br>";
    echo htmlspecialchars($livre['genre']) . "<br>";
    echo "<hr>";
}
?>
