<?php
session_start();

if (isset($_POST['fonction']) && $_POST['fonction'] == "user") {
    create_user();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "carte") {
    create_carte();
}
elseif (isset($_POST['fonction']) && $_POST['fonction'] == "son")
{
    create_son();
}
elseif (isset($_POST['fonction']) && $_POST['fonction'] == "image")
{
    create_image();
}
elseif(isset($_POST['fonction']) && $_POST['fonction'] == "serie")
{
    create_serie();
}
elseif(isset($_POST['fonction']) && $_POST['fonction']== "attribution")
{
    attribuer_serie_to_user();
}
function create_user()
{
    include('../config/db_config.php');

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $parent1 = $_POST['parent1'];
    $parent2 = $_POST['parent2'];
    $mail = $_POST['email'];
    $telephone = $_POST['telephone'];
    $idRole = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    

    $stmt = $db->prepare("insert into user (Nom, Prenom, Parent1, Parent2, Mail, Telephone, Password, IdRole) values(:nom,:prenom,:parent1,:parent2,:mail,:telephone,:password,:idRole)");
    if ($stmt->execute(['nom'=>$nom,'prenom'=>$prenom, 'parent1'=>$parent1,'parent2'=>$parent2,'mail'=>$mail,'telephone'=>$telephone,'password'=>$password,'idRole'=>$idRole])) {
        $IdPatient = $db->lastInsertId();

        linkOrtho2Patient($_SESSION['IdUser'],$IdPatient);

        header("Location: ../accueil.php");
        exit();
    }
}

function linkOrtho2Patient($Idortho, $IdPatient)
{
    include('../config/db_config.php');
    $stmt = $db->prepare("insert into p2o (IdOrtho, IdPatient) values (:IdOrtho, :IdPatient)");
    $stmt->execute(['IdOrtho'=>$Idortho,'IdPatient'=>$IdPatient]);
}

function create_carte()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $idImage = $_POST['image'];
    $idImageReel = $_POST['imageReel'];
    $idSon = $_POST['son'];
    $idSerie = $_POST['serie'];
    $description = $_POST['description'];

    $stmt = $db->prepare("insert into carte (Intitule, IdImage, IdImageReel, IdSon, IdSerie, description) values(:intitule,:idImage, :idImageReel, :idSon,:idSerie,:description)");
    if ($stmt->execute(['intitule'=>$intitule, 'idImage'=>$idImage, 'idImageReel'=>$idImageReel, 'idSon'=>$idSon, 'idSerie'=>$idSerie, 'description'=>$description])) {
        header("Location: ../accueil.php");
        exit();
    }
}

function create_son()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $file = $_FILES["fichier"];

    $stmt = $db->prepare("insert into son (Intitule) values (:intitule)");
    if($stmt->execute(['intitule'=>$intitule])) {
        $last_id = $db->lastInsertId();

        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $last_id.".".$fileExtension;
        $destination = "../son/" . $newFileName;

        if(move_uploaded_file($file["tmp_name"], $destination))
        {
            header("Location: ../accueil.php");
            exit();
        }
        else
        {
            echo "erreur lors du telechargement du fichier";
        }
    }
}

function create_image()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $file = $_FILES["fichier"];
    $type = $_POST['type'];

    $stmt = $db->prepare("insert into image (Intitule, typeImage) values(:intitule, :type)");
    if($stmt->execute(['intitule' => $intitule,'type'=>$type])) {
        $last_id = $db->lastInsertId();

        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $last_id.".".$fileExtension;
        $destination = "../image/" . $newFileName;

        if(move_uploaded_file($file["tmp_name"], $destination))
        {
            header("Location: ../accueil.php");
            exit();
        }
        else
        {
            echo "erreur lors du telechargement de l'image";
        }
    }
}

function create_serie()
{
    include('../config/db_config.php');

    $theme = $_POST['theme'];
    $description = $_POST["description"];

    $stmt = $db->prepare("insert into serie (Theme, description) values(:theme,:description)");
    if($stmt->execute(['theme' => $theme, 'description' => $description]))
    {
        header("Location: ../accueil.php");
        exit();
    }
}

function attribuer_serie_to_user()
{
    include('../config/db_config.php');

    $idUser = $_POST['user'];
    $idSerie = $_POST["serie"];

    $stmt = $db->prepare("insert into serietouser (IdUser, IdSerie, IdStatut) values(:idUser,:idSerie,:idStatut)");
    if($stmt->execute(['idUser' => $idUser, 'idSerie' => $idSerie,'idStatut'=>1]))
    {
        header("Location: ../accueil.php");
        exit();
    }
}