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
    <h1>Bibliothèque</h1>
    <?php



    if (!isset($_SESSION['user'])) {
        echo '<form method="POST">
        <label>Identifiant</label>
        <input type="text" name="identifiant">
        <label>Password</label>
        <input type="password" name="password">
        <input type="submit" name="submitConnection" value="Se connecter">
    </form>
    <a href="?page=createAccount"><p>Créez mon compte</p></a>';
    } else {
        echo '<form method="POST">
    <input type="submit" name="deconnexion" value="Deconnexion">
</form>';

        echo "Bonjour, " . htmlspecialchars($_SESSION['user']['nomUser']) . " " . htmlspecialchars($_SESSION['user']['prenomUser']) . ". Vous êtes connecté.";
        include 'adminBoard.php';
    }





    if (isset($_POST['submitConnection']) && !empty($_POST['identifiant']) && !empty($_POST['password'])) {
        $id = $_POST['identifiant'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM `users` WHERE adresseMailUser = '$id'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);



        if (isset($results[0]["passwordUser"])) {

            if (password_verify($password, $results[0]['passwordUser'])) {
                $_SESSION['user'] = [
                    "idUser" => htmlspecialchars($results[0]['idUser']),
                    "nomUser" => htmlspecialchars($results[0]['nomUser']),
                    "prenomUser" => htmlspecialchars($results[0]['prenomUser']),
                    "ageUser" => htmlspecialchars($results[0]['ageUser']),
                    "adresseMailUser" => htmlspecialchars($results[0]['adresseMailUser'])
                ];
                header("Location: logUser.php");
            }
        } else {
            echo "mail ou mot de passe incorrect";
        }
    }

    if (isset($_POST['deconnexion'])) {
        session_destroy();
        header("Location: logUser.php");
    }

    echo '<a href="?page=createAccount"><p>Créez mon compte</p></a>';

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