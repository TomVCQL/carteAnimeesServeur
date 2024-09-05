<?php
// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    include("../config/db_config.php");

    $stmt = $db->prepare("SELECT password FROM user WHERE Mail = :mail");
    $stmt->execute(['mail'=>$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() == 1) {
        $hashed_password = $result["password"];

        if (password_verify($_POST['password'], $hashed_password)) {

            $stmt = $db->prepare("SELECT id, Nom, Prenom, IdRole FROM user WHERE Mail = :mail");
            $stmt->execute(['mail'=>$email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result['IdRole'] == 2)
            {
                header("Location: ../index.php?erreur=4");
                exit();
            }

            $_SESSION["nom"] = $result["Nom"];
            $_SESSION["prenom"] = $result["Prenom"];
            $_SESSION['IdUser'] = $result['id'];
            $_SESSION['IdRole'] = $result['IdRole'];
            
            $stmt = $db->prepare("SELECT NomRole FROM role WHERE id = :idRole");
            $stmt->execute(['idRole'=>$result["IdRole"]]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["role"] = $role["NomRole"];

            header("Location: ../accueil.php");
            exit();

        } else {
            header("Location: ../index.php?erreur=1");
            exit();
        }
    } else {
        header("Location: ../index.php?erreur=2");
        exit();
    }
}
?>