<?php

session_start();
$host = 'localhost';
$dbname = 'bibliothèque';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Active les erreurs PDO en exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie !";
    // echo "<br>";
    // echo "salut";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Bibliothèque En Ligne</h1>

    <?php

    if (!isset($_SESSION['utilisateurs'])) {
        echo '<form method="POST">
        <label>Nom</label>
        <input type="text" name="nom">
        <br>
        <label>Adresse mail</label>
        <input type="mail" name="mail">
        <br>
        <input type="submit" name="submitConnection" value="Se connecter">
    </form>
    <a href="?page=createAccount"><p>Créez mon compte</p></a>';
    } else {
        $adminEmail = "admin@mail.com";

echo "Bonjour, " . htmlspecialchars($_SESSION['utilisateurs']['nomUtilisateur']) . " " . htmlspecialchars($_SESSION['utilisateurs']['prenomUtilisateur']);

        if ($_SESSION['utilisateurs']['emailUtilisateur'] == $adminEmail) {
        echo " Vous êtes connecté en tant qu'administrateur.";
        include 'indexAdmin.php'; // admin
    } else {
        echo " Vous êtes connecté en tant qu'utilisateur.";
        include 'indexUsers.php'; // utilisateur simple
    }

        echo '<form method="POST">
    <input type="submit" name="deconnexion" value="Deconnexion">
</form>';

    }

    if (isset($_POST['submitConnection']) && !empty($_POST['nom']) && !empty($_POST['mail'])) {
        $nom = $_POST['nom'];
        $mail = $_POST['mail'];

        $sql = "SELECT * FROM `utilisateurs` WHERE emailUtilisateur = :mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($results[0]["emailUtilisateur"])) {

            if (!empty($results)) {
                $_SESSION['utilisateurs'] = [
                    "idUtilisateur" => htmlspecialchars($results[0]['idUtilisateur']),
                    "nomUtilisateur" => htmlspecialchars($results[0]['nomUtilisateur']),
                    "prenomUtilisateur" => htmlspecialchars($results[0]['prenomUtilisateur']),
                    "emailUtilisateur" => htmlspecialchars($results[0]['emailUtilisateur'])
                ];
                header("Location: index.php");
            }
        } else {
            echo "nom ou mail incorrect";
        }
    }

    if (isset($_POST['deconnexion'])) {
        session_destroy();
        header("Location: index.php");
    }

    if (isset($_GET['page']) && $_GET['page'] == 'createAccount') {
        echo '<form method="POST">
    <label>Nom</label>
    <input type="text" name="nomCreate" value="">
    <br>
    <label>Prenom</label>
    <input type="text" name="prenomCreate" value="">
    <br>
    <label>Age</label>
    <input type="text" name="ageCreate" value="">
    <br>
    <label>Mail</label>
    <input type="text" name="mailCreate" value="">
    <br>
    <input type="submit" name="submitCreate" value="Créez un compte">
</form>';
    }

    if (isset($_POST['submitCreate'])) {
        $nomCreate = $_POST['nomCreate'];
        $prenomCreate = $_POST['prenomCreate'];
        $ageCreate = $_POST['ageCreate'];
        $mailCreate = $_POST['mailCreate'];

        $sqlCreate = "INSERT INTO `utilisateurs`(`nomUtilisateur`, `prenomUtilisateur`, `emailUtilisateur`) 
            VALUES (?,?,?)";
        $stmtCreate = $pdo->prepare($sqlCreate);

        $stmtCreate->execute([$nomCreate, $prenomCreate, $mailCreate]);
    }

    ?>
</body>

</html>