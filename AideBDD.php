<!-- doc user -->

// Traitement de l'emprunt
<?php
if (isset($_POST['emprunter'])) {
    $idLivre = $_POST['idLivre'];
    $idUtilisateur = $_SESSION['utilisateurs']['idUtilisateur'];
    $dateEmprunt = date('Y-m-d');
   

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

// Afficher les livres empruntés par l'utilisateur connecté
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

    echo "Livre rendu avec succès !<br><br>";
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




// a mettre dans le foreach de result livre

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

?>


    <!-- A METTRE A LA FIN DE INDEX ADMIN -->

    <hr>
<h2>Historique des emprunts</h2>
<a href="?page=viewHistorique">
    <p>Afficher l'historique</p>
</a>

<?php
if (isset($_GET['page']) && $_GET['page'] == 'viewHistorique') {
    
    $sqlHistorique = "
        SELECT 
            emprunts.idEmprunt,
            emprunts.dateEmprunt,
            emprunts.dateRetour,
            emprunts.rendu,
            CONCAT(utilisateurs.prenomUtilisateur, ' ', utilisateurs.nomUtilisateur) AS utilisateur,
            utilisateurs.emailUtilisateur,
            livres.nomLivre,
            CONCAT(ecrivains.prenomEcrivain, ' ', ecrivains.nomEcrivain) AS auteur
        FROM emprunts
        INNER JOIN utilisateurs ON emprunts.idUtilisateur = utilisateurs.idUtilisateur
        INNER JOIN livres ON emprunts.idLivre = livres.idLivre
        INNER JOIN ecrivains ON livres.idEcrivain = ecrivains.idEcrivain
        ORDER BY emprunts.dateEmprunt DESC
    ";

    $stmtHistorique = $pdo->prepare($sqlHistorique);
    $stmtHistorique->execute();
    $resultsHistorique = $stmtHistorique->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Historique complet des emprunts</h3>";

    foreach ($resultsHistorique as $emprunt) {
        echo "Utilisateur : " . htmlspecialchars($emprunt['utilisateur']) . " (" . htmlspecialchars($emprunt['emailUtilisateur']) . ")<br>";
        echo "Livre : " . htmlspecialchars($emprunt['nomLivre']) . "<br>";
        echo "Auteur : " . htmlspecialchars($emprunt['auteur']) . "<br>";
        echo "Date d'emprunt : " . htmlspecialchars($emprunt['dateEmprunt']) . "<br>";
        
        if ($emprunt['rendu'] == 1) {
            echo "Statut : <span style='color: green;'>Rendu</span><br>";
            echo "Date de retour : " . htmlspecialchars($emprunt['dateRetour']) . "<br>";
        } else {
            echo "Statut : <span style='color: orange;'>En cours d'emprunt</span><br>";
        }
        
        echo "<hr>";
    }
}
?>


</body>

</html>