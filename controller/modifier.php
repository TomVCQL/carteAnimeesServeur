<?php
session_start();

if (isset($_POST['fonction']) && $_POST['fonction'] == "user") {
    modify_user();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "carte") {
    modify_carte();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "serie") {
    modify_serie();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "son") {
    modify_son();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "image") {
    modify_image();
} else {
    header("Location: ../accueil.php");
    exit();
}
function modify_user()
{
    include ('../config/db_config.php');
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $telephone = $_POST['telephone'];
    $id = $_POST['id'];
    $role = $_POST['role'];
    $parent1 = $_POST['parent1'];
    $parent2 = $_POST['parent2'];
    
    
    print_r($role);

    // Récupération des ortho déja attribuer 
    $stmt = $db->prepare("SELECT IdOrtho, Id  FROM p2o WHERE IdPatient = :idPatient");
    $stmt->execute(['idPatient' => $id]);
    $tabAttribOrtho= $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère directement les IDs en tant que tableau



    $stmt = $db->prepare("  UPDATE user
                            SET Nom = :nom, Prenom = :prenom, Mail = :mail, parent1 = :parent1, parent2 = :parent2, Telephone = :telephone
                            WHERE Id = :id");
    
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'mail' => $mail, 'parent1' =>$parent1, 'parent2'=>$parent2, 'telephone' => $telephone, 'id' => $id]);
    
    if($role==2){

        $tabOrtho = $_POST['ortho'];

            // Suppression de la carte dans une série
            foreach ($tabAttribOrtho as $attribOrtho) {
            $exist = false;
            for ($j = 0; $j < count($tabOrtho); $j++) {
                if ($attribOrtho['IdOrtho'] == $tabOrtho[$j]) {
                    $exist = true;
                    break; 
                }
            }
        
            if (!$exist) {
                //echo "Suppression " . htmlspecialchars($attribOrtho['Id']) . "<br>";
                $stmt = $db->prepare("DELETE FROM p2o WHERE id = :Id");
                $stmt->execute(['Id' => $attribOrtho['Id']]);
            }


        }

         // ajout de la carte dans une serie 
        for ($i = 0; $i < count($tabOrtho); $i++) {
        $exist = false;
    
            foreach ($tabAttribOrtho as $attribOrtho) {
                if ($tabOrtho[$i] == $attribOrtho['IdOrtho']) {
                    $exist = true;
                    break; // Arrête la boucle interne si une correspondance est trouvée
                }
                
            }

            if (!$exist) {
                //echo "Ajouter " . $tabOrtho[$i] . " et l'utilisateur ".$id."<br>";
                $stmt = $db->prepare("INSERT INTO p2o (IdOrtho,IdPatient) VALUES (:idOrtho,:idPatient)");
                $stmt->execute(['idOrtho'=>$tabOrtho[$i],'idPatient'=>$id]);

            }
        }



    }
    header("Location: ../accueil.php");
    exit();
}


function modify_carte()
{
    include('../config/db_config.php');

    $intitule = $_POST['intitule'];
    $idImage = $_POST['image'];
    $idSon = $_POST['son'];
    $tabSerie = $_POST['serie'];
    $description = $_POST['description'];
    $id = $_POST['carte'];
    $proprietaire = $_POST['proprietaire'];
    $orthophoniste = isset($_POST["orthophoniste"]) && is_array($_POST["orthophoniste"]) ? $_POST["orthophoniste"] : [];




    // Récupération des séries déjà attribuées
    if ($_SESSION['IdRole'] == '1') {
        $stmt = $db->prepare("SELECT cs.IdSerie, cs.id  FROM cartetoserie cs,serie s WHERE s.proprietaire = :idUser and s.id = cs.IdSerie and cs.IdCarte = :idCarte");
        $stmt->execute(['idCarte' => $id, 'idUser' => $_SESSION['IdUser']]);
    } elseif ($_SESSION['IdRole'] == '3') {
        $stmt = $db->prepare("SELECT IdSerie, id  FROM cartetoserie WHERE IdCarte = :idCarte");
        $stmt->execute(['idCarte' => $id]);
    }

    $tabAttribSerie = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère directement les IDs en tant que tableau

    $stmt = $db->prepare("  UPDATE carte
                            SET Intitule = :intutile, IdImage = :idImage, IdSon = :idSon, description = :description  WHERE Id = :id");
    $stmt->execute(['intutile' => $intitule, 'idImage' => $idImage, 'idSon' => $idSon, 'description' => $description, 'id' => $id]);



    // Suppression de la carte dans une série
    foreach ($tabAttribSerie as $attribSerie) {
        $exist = false;
        for ($j = 0; $j < count($tabSerie); $j++) {
            if ($attribSerie['IdSerie'] == $tabSerie[$j]) {
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            //echo "Suppression " . htmlspecialchars($attribSerie['id']) . "<br>";
            $stmt = $db->prepare("DELETE FROM cartetoserie WHERE id = :Id");
            $stmt->execute(['Id' => $attribSerie['id']]);
        }
    }

    // ajout de la carte dans une serie 
    for ($i = 0; $i < count($tabSerie); $i++) {
        $exist = false;

        foreach ($tabAttribSerie as $attribSerie) {
            if ($tabSerie[$i] == $attribSerie['IdSerie']) {
                $exist = true;
                break; // Arrête la boucle interne si une correspondance est trouvée
            }
        }


        if (!$exist) {
            //echo "Ajouter " . $tabSerie[$i] . " et la carte ".$id."<br>";
            $stmt = $db->prepare("INSERT INTO cartetoserie (IdCarte,IdSerie) VALUES (:idCarte,:idSerie)");
            $stmt->execute(['idCarte' => $id, 'idSerie' => $tabSerie[$i]]);
        }
    }



    // récupération liste ortho partage pour la carte
    $stmt = $db->prepare("SELECT IdUser,id FROM cartetoortho WHERE IdCarte = :idCarte and IdUser <> :idUser");
    $stmt->execute(['idCarte' => $id, 'idUser' => $proprietaire]);
    $tabOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($tabOrtho);

    // Suppression du partage ortho
    foreach ($tabOrtho as $ortho) {
        $exist = false;
        for ($j = 0; $j < count($orthophoniste); $j++) {
            if ($ortho['IdUser'] == $orthophoniste[$j]) {
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            //echo "Suppression " . htmlspecialchars($ortho['IdUser']) . "<br>";
            $stmt = $db->prepare("DELETE FROM cartetoortho WHERE id = :Id");
            $stmt->execute(['Id' => $ortho['id']]);
            
            // récupération des series de l'ortho 
            $stmt = $db->prepare("SELECT Id FROM serie WHERE proprietaire = :idOrtho");
            $stmt->execute(['idOrtho' => $ortho['IdUser']]);
            $tabSerieOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            foreach ($tabSerieOrtho as $serie) {
            echo "Suppression " . htmlspecialchars($ortho['IdUser']) . "<br>";
             //suppression des carte dans les series de l'ortho qui na plus le partage      
            $stmt = $db->prepare("DELETE FROM cartetoserie WHERE IdCarte = :idCarte and IdSerie = :idSerie");
            $stmt->execute(['idCarte' => $id,'idSerie'=>$serie['Id']]);

            }

        }
    }

    // ajout de la carte dans une serie 
    for ($i = 0; $i < count($orthophoniste); $i++) {
        $exist = false;

        foreach ($tabOrtho as $ortho) {
            if ($orthophoniste[$i] == $ortho['IdUser']) {
                $exist = true;
                break; // Arrête la boucle interne si une correspondance est trouvée
            }
        }


        if (!$exist) {
            //echo "Ajouter " . $ortho[$i] ."<br>";
            $stmt = $db->prepare("INSERT INTO cartetoortho (IdCarte,IdUser) VALUES (:idCarte,:idUser)");
            $stmt->execute(['idCarte' => $id, 'idUser' => $orthophoniste[$i]]);
        }
    }



    header("Location: ../accueil.php");
    exit();
}
function modify_serie()
{
    include('../config/db_config.php');
    $theme = $_POST['theme'];
    $description = $_POST['description'];
    $id = $_POST['serie'];
    $orthophoniste = isset($_POST["orthophoniste"]) && is_array($_POST["orthophoniste"]) ? $_POST["orthophoniste"] : [];
    $proprietaire = $_POST['proprietaire'];


    // récupération ortho partage series
    $stmt = $db->prepare("SELECT IdUser FROM serietoortho WHERE IdSerie = :idSerie and IdUser <> :idUser");
    $stmt->execute(['idSerie' => $id, 'idUser' => $proprietaire]);
    $tabOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère directement les IDs en tant que tableau


    foreach ($tabOrtho as $ortho) {
        $exist = false;
        for ($j = 0; $j < count($orthophoniste); $j++) {
            if ($ortho['IdUser'] == $orthophoniste[$j]) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            //echo "Suppression " . htmlspecialchars($ortho['IdUser']) . "<br>";
            $stmt = $db->prepare("DELETE FROM serietoortho WHERE IdUser = :idUser and IdSerie = :idSerie");
            $stmt->execute(['idUser' => $ortho['IdUser'], 'idSerie' => $id]);

            // vérification ortho accès carte patient
            $stmt = $db->prepare("SELECT IdUser FROM serietouser where IdSerie = :idSerie;");
            $stmt->execute(['idSerie' => $id]);
            $tabPatient = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($tabPatient as $patient) {

                $stmt = $db->prepare("SELECT su.IdUser, po.IdOrtho, su.IdSerie, so.id
                from serietouser su
                INNER JOIN p2o po ON su.IdUser = po.IdPatient and su.IdUser = :idPatient
                LEFT OUTER JOIN serietoortho so ON so.IdUser = po.IdOrtho and su.IdSerie = so.IdSerie
                where su.IdSerie = :idSerie;");
                $stmt->execute(['idSerie' => $id, 'idPatient' => $patient['IdUser']]);
                $tabShare = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $autoriser = false;
                foreach ($tabShare as $share){

                    if (!empty($share['id'])) { // Vérifiez si 'id' n'est pas vide
                        $autoriser = true; 
                        break; 
                    }

                }
                if(!$autoriser){
                    // supppression de l'accès du patient 
                    echo "le patient ".$patient['IdUser']." na plus l'accès";
                    $stmt = $db->prepare("DELETE FROM serietouser WHERE IdSerie = :idSerie and IdUser = :idUser");
                    $stmt->execute(['idSerie' => $id,'idUser'=>$patient['IdUser']]);

                }
            
        
            }

       
        }
    }

    // ajout partage ortho
    for ($i = 0; $i < count($orthophoniste); $i++) {
        $exist = false;

        foreach ($tabOrtho as $ortho) {
            if ($orthophoniste[$i] == $ortho['IdUser']) {
                $exist = true;
                break; // Arrête la boucle interne si une correspondance est trouvée
            }
        }
        if (!$exist) {
            echo "Ajouter " . $orthophoniste[$i] . "<br>";
            $stmt = $db->prepare("INSERT INTO serietoortho (IdUser,IdSerie) VALUES (:idUser,:idSerie)");
            $stmt->execute(['idUser' => $orthophoniste[$i], 'idSerie' => $id]);
        }
    }

    $stmt = $db->prepare("  UPDATE serie
                            SET Theme = :theme, description = :description
                            WHERE Id = :id");
    if ($stmt->execute(['theme' => $theme, 'description' => $description, 'id' => $id])) {
        header("Location: ../accueil.php");
        exit();
    }
}



function modify_son()
{
    include('../config/db_config.php');
    $intitule = $_POST['intitule'];
    $file = $_FILES["fichier"];
    $id = $_POST['son'];

    if ($file['name'] != "") {
        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $id . "." . $fileExtension;
        $destination = "../son/" . $newFileName;

        if (file_exists($destination)) {
            unlink($destination);
            move_uploaded_file($file["tmp_name"], $destination);
        }
    }

    $stmt = $db->prepare("  UPDATE son
    SET Intitule = :intitule
    WHERE Id = :id");

    if ($stmt->execute(['intitule' => $intitule, 'id' => $id])) {
        header("Location: ../accueil.php");
        exit();
    }
}

function modify_image()
{
    include('../config/db_config.php');
    $intitule = $_POST['intitule'];
    $file = $_FILES["fichier"];
    $type = $_POST["type"];
    $id = $_POST['image'];

    if ($file['name'] != "") {
        $fileName = basename($file["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newFileName = $id . "." . $fileExtension;
        $destination = "../image/" . $newFileName;

        if (file_exists($destination)) {
            unlink($destination);
            move_uploaded_file($file["tmp_name"], $destination);
        }
    }

    $stmt = $db->prepare("  UPDATE image
    SET Intitule = :intitule, typeImage = :type
    WHERE Id = :id");

    if ($stmt->execute(['intitule' => $intitule, 'type' => $type, 'id' => $id])) {
        header("Location: ../accueil.php");
        exit();
    }
}
