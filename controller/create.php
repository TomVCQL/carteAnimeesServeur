<?php
session_start();

if (isset($_POST['fonction']) && $_POST['fonction'] == "user") {
    create_user();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "carte") {
    create_carte();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "son") {
    create_son();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "image") {
    create_image();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "serie") {
    create_serie();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "attribution") {
    attribuer_serie_to_user();
}
function create_user()
{
    include('../config/db_config.php');

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['email'];
    $telephone = $_POST['telephone'];
    $idRole = $_POST['role'];
    $parent1 = $_POST['parent1'];
    $parent2 = $_POST['parent2'];
    
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $stmt = $db->prepare("insert into user (Nom, Prenom, Mail, parent1, parent2, Telephone, Password, IdRole) values(:nom,:prenom,:mail,:parent1,:parent2,:telephone,:password,:idRole)");
    if ($stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'mail' => $mail, 'parent1'=>$parent1,'parent2'=>$parent2,'telephone' => $telephone, 'password' => $password, 'idRole' => $idRole])) {
        $IdPatient = $db->lastInsertId();

        if (isset($_POST['ortho'])  && $_POST['role'] == 2 && $_SESSION['IdRole'] == 3) {
            $orthos = $_POST['ortho'];
            foreach ($orthos as $ortho) {
                linkOrtho2Patient($ortho, $IdPatient);
            }
        } else if ($_POST['role'] == 2) {
            linkOrtho2Patient($_SESSION['IdUser'], $IdPatient);
        }

        header("Location: ../accueil.php");
        exit();
    }
}

function linkOrtho2Patient($Idortho, $IdPatient)
{
    include('../config/db_config.php');
    $stmt = $db->prepare("insert into p2o (IdOrtho, IdPatient) values (:IdOrtho, :IdPatient)");
    $stmt->execute(['IdOrtho' => $Idortho, 'IdPatient' => $IdPatient]);
}

function create_carte()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $idImage = $_POST['image'];
    $idImageReel = $_POST['imageReel'];
    $idSon = $_POST['son'];
    $tabSerie = $_POST['serie'];
    $description = $_POST['description'];
    $proprietaire = $_POST['proprietaire'];
    $tabUser = isset($_POST["orthophoniste"]) && is_array($_POST["orthophoniste"]) ? $_POST["orthophoniste"] : [];    
    array_push($tabUser,$proprietaire);
    
    $stmt = $db->prepare("insert into carte (Intitule, IdImage, IdImageReel, IdSon, description,proprietaire) values(:intitule,:idImage, :idImageReel, :idSon,:description,:proprietaire)");
    $stmt->execute(['intitule' => $intitule, 'idImage' => $idImage, 'idImageReel' => $idImageReel, 'idSon' => $idSon,  'description' => $description,'proprietaire'=>$proprietaire]);

    $idCarte = $db->lastInsertId();
    for ($i = 0; $i < count($tabSerie); $i++) {
        //echo $tabSerie[$i];
        $stmt = $db->prepare("insert into cartetoserie (IdCarte,IdSerie) values(:idCarte,:idSerie)");
        $stmt->execute(['idCarte' => $idCarte, 'idSerie' => $tabSerie[$i]]);
    }
    for ($i = 0; $i < count($tabUser); $i++) {
        //echo $tabSerie[$i];
        $stmt = $db->prepare("insert into cartetoortho (IdCarte, IdUser) values(:idCarte,:idUser)");         
        $stmt->execute(['idCarte' => $idCarte, 'idUser' => $tabUser[$i]]);

    }
    header("Location: ../accueil.php");
    exit();
}

function create_son()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $file = $_FILES["fichier"];
    $role = $_SESSION['role'];

    

    if ($role == 'admin') {
        $statut = 'validé';
    } else {
        $statut = 'attente';
    }



    $stmt = $db->prepare("insert into son (Intitule,statut) values (:intitule,:statut)");
    if ($stmt->execute(['intitule' => $intitule,'statut'=>$statut])) {
        $last_id = $db->lastInsertId();

        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $last_id . "." . $fileExtension;
        $destination = "../son/" . $newFileName;

        if (move_uploaded_file($file["tmp_name"], $destination)) {
            
            if ($statut == 'attente') {
                $stmt = $db->prepare("INSERT INTO demandeajout (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
                $stmt->execute(['typeobjet' => 'son','nomidentifiant'=> 'Id', 'valeuridentifiant'=> $last_id, 'utilisateur'=> $_SESSION['IdUser']]);
            }

            header("Location: ../accueil.php");
            exit();
        } else {
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
    $role = $_SESSION['role'];


    if ($role == 'admin') {
        $statut = 'validé';
    } else {
        $statut = 'attente';
    }



    $stmt = $db->prepare("insert into image (Intitule, typeImage,statut) values(:intitule, :type,:statut)");
    if ($stmt->execute(['intitule' => $intitule, 'type' => $type, 'statut' => $statut])) {
        $last_id = $db->lastInsertId();

        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $last_id . "." . $fileExtension;
        $destination = "../image/" . $newFileName;

        if (move_uploaded_file($file["tmp_name"], $destination)) {
            if ($statut == 'attente') {
                $stmt = $db->prepare("INSERT INTO demandeajout (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
                $stmt->execute(['typeobjet' => 'image','nomidentifiant'=> 'Id', 'valeuridentifiant'=> $last_id, 'utilisateur'=> $_SESSION['IdUser']]);
            }
            header("Location: ../accueil.php");
            exit();
        } else {
            echo "erreur lors du telechargement de l'image";
        }
    }
}


function create_serie()
{
    include('../config/db_config.php');

    $theme = $_POST['theme'];
    $description = $_POST["description"];
    $tabUser = isset($_POST["orthophoniste"]) && is_array($_POST["orthophoniste"]) ? $_POST["orthophoniste"] : [];    
    $proprietaire = $_POST['proprietaire'];
    array_push($tabUser,$proprietaire);
    


    $stmt = $db->prepare("insert into serie (Theme, description,proprietaire) values(:theme,:description,:proprietaire)");
    if ($stmt->execute(['theme' => $theme, 'description' => $description,'proprietaire'=>$proprietaire])) {
        
        $idSerie = $db->lastInsertId();   
        for ($i = 0; $i < count($tabUser); $i++) {
            //echo $tabSerie[$i];
            $stmt = $db->prepare("insert into serietoortho (IdSerie, IdUser) values(:idSerie,:idUser)");         
            $stmt->execute(['idSerie' => $idSerie, 'idUser' => $tabUser[$i]]);
    
        }
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
    if ($stmt->execute(['idUser' => $idUser, 'idSerie' => $idSerie, 'idStatut' => 1])) {
        header("Location: ../accueil.php");
        exit();
    }
}
