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
    <label>Password</label>
    <input type="password" name="passwordCreate" value="">
    <br>
    <input type="submit" name="submitCreate" value="Créez un compte">
</form>';
    }







    ?>
</body>
</html>

