<?php
session_start();

if (isset($_POST['fonction']) && $_POST['fonction'] == "user") {
    supprimer_user();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "carte") {
    supprimer_carte();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "serie") {
    supprimer_serie();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "son") {
    supprimer_son();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "image") {
    supprimer_image();
}

function supprimer_user()
{
    include('../config/db_config.php');
    $userId = $_POST['user'];

    $stmt = $db->prepare("  DELETE
                            FROM serietouser
                            WHERE IdUser = :id");
    if ($stmt->execute(['id' => $userId])) {

        $stmt = $db->prepare("  DELETE
                            FROM p2o
                            WHERE IdPatient = :id");
        if ($stmt->execute(['id' => $userId])) {
            $stmt = $db->prepare("  DELETE
                            FROM user
                            WHERE Id = :id");

            if ($stmt->execute(['id' => $userId])) {
                header("Location: ../view/user.php");
                exit();
            }
        }
    }
}

function supprimer_carte()
{
    include('../config/db_config.php');
    $idCarte = $_POST['carte'];

    if ($_SESSION['role'] == 'admin') {

        // suppresion cartetoserie
        $stmt = $db->prepare("  DELETE
                            FROM cartetoserie
                            WHERE IdCarte = :id");

        // suppression carte 
        if ($stmt->execute(['id' => $idCarte])) {


            //suppression carte to ortho 
            $stmt = $db->prepare("  DELETE FROM cartetoortho WHERE IdCarte = :id");
            if ($stmt->execute(['id' => $idCarte])) {
            
            
                $stmt = $db->prepare("  DELETE
                FROM carte
                WHERE Id = :id");


                    if ($stmt->execute(['id' => $idCarte])) {

                        // suppression des demande et duplicat 
                        $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
                        $stmt->execute(['typeobjet' => 'carte', 'valeurIdentifiant' => $idCarte]);

                        header("Location: ../view/carte.php");
                        exit();
                    }
            }
           
        }
    } else {
        try {

            // creation demande 
            $stmt = $db->prepare("INSERT INTO demandesuppression (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
            if ($stmt->execute(['typeobjet' => 'carte', 'nomidentifiant' => 'Id', 'valeuridentifiant' => $idCarte, 'utilisateur' => $_SESSION['IdUser']])) {
                header("Location: ../accueil.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Vous avez déja fait une demande pour cette carte";
            header("Location: ../view/carte.php");
            exit();
        }
    }
}
function supprimer_serie()
{
    include('../config/db_config.php');

    $idSerie = $_POST['serie'];

    if ($_SESSION['role'] == 'admin') {

        // suppression seriestouser
        $stmt = $db->prepare("  DELETE  FROM serietouser WHERE IdSerie = :id");
        if ($stmt->execute(['id' => $idSerie])) {

            // suppression cartetoseries
            $stmt = $db->prepare("  DELETE  FROM cartetoserie WHERE IdSerie = :id");
            if ($stmt->execute(['id' => $idSerie])) {

                //suppression serietoortho
                $stmt = $db->prepare("  DELETE FROM serietoortho WHERE IdSerie = :id");
                if($stmt->execute(['id' => $idSerie])){
                    
                    // suppression series
                    $stmt = $db->prepare("  DELETE  FROM serie WHERE Id = :id");
                    $stmt->execute(['id' => $idSerie]);

                    $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
                    $stmt->execute(['typeobjet' => 'serie', 'valeurIdentifiant' => $idSerie]);


                    header("Location: ../view/serie.php");
                    exit();
                }
        
            }
        }
    } else {
        try {

            // creation demande 
            $stmt = $db->prepare("INSERT INTO demandesuppression (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
            if ($stmt->execute(['typeobjet' => 'serie', 'nomidentifiant' => 'Id', 'valeuridentifiant' => $idSerie, 'utilisateur' => $_SESSION['IdUser']])) {
                header("Location: ../accueil.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Vous avez déja fait une demande pour cette serie";
            header("Location: ../view/serie.php");
            exit();
        }
    }
}

function supprimer_son()
{
    include('../config/db_config.php');

    $idSon = $_POST['son'];

    if ($_SESSION['role'] == 'admin') {

        // mettre a null les carte qui utilise le son 
        $stmt = $db->prepare("UPDATE carte set IdSon =NULL WHERE IdSon = :id");

        if ($stmt->execute(['id' => $idSon])) {
            // suppression du son 
            $stmt = $db->prepare("DELETE FROM son WHERE Id = :id");
            if ($stmt->execute(['id' => $idSon])) {

                // suppression de l'element depuis son emplacement 
                $destination = "../son/" . $idSon;

                $files = glob($destination . '.*');

                if (empty($files)) {
                    echo "Erreur : Aucun fichier nommé $idSon avec une extension valide trouvé.";
                } else {
                    // Supprimer le premier fichier trouvé
                    if (unlink($files[0])) {
                        echo "Le fichier " . basename($files[0]) . " a été supprimé avec succès.";
                    } else {
                        echo "Erreur : Impossible de supprimer le fichier " . basename($files[0]) . ".";
                    }
                }

                $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
                $stmt->execute(['typeobjet' => 'son', 'valeurIdentifiant' => $idSon]);

                header("Location: ../view/son.php");
                exit();
            }
        }
    } else {

        try {

            // creation demande 
            $stmt = $db->prepare("INSERT INTO demandesuppression (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
            if ($stmt->execute(['typeobjet' => 'son', 'nomidentifiant' => 'Id', 'valeuridentifiant' => $idSon, 'utilisateur' => $_SESSION['IdUser']])) {
                header("Location: ../accueil.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Vous avez déja fait une demande pour ce son";
            header("Location: ../view/son.php");
            exit();
        }

    }

}

function supprimer_image()
{
    include('../config/db_config.php');

    $idImage = $_POST['image'];

    if ($_SESSION['role'] == 'admin') {

        // mettre a null les carte qui utilise le son 
        $stmt = $db->prepare("UPDATE carte set IdImage =NULL WHERE IdImage = :id");

        if ($stmt->execute(['id' => $idImage])) {
            // suppression du son 
            $stmt = $db->prepare("DELETE FROM image WHERE Id = :id");
            if ($stmt->execute(['id' => $idImage])) {

                // suppression de l'element depuis son emplacement 
                $destination = "../image/" . $idImage;

                $files = glob($destination . '.*');

                if (empty($files)) {
                    echo "Erreur : Aucun fichier nommé $idImage avec une extension valide trouvé.";
                } else {
                    // Supprimer le premier fichier trouvé
                    if (unlink($files[0])) {
                        echo "Le fichier " . basename($files[0]) . " a été supprimé avec succès.";
                    } else {
                        echo "Erreur : Impossible de supprimer le fichier " . basename($files[0]) . ".";
                    }
                }

                $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
                $stmt->execute(['typeobjet' => 'image', 'valeurIdentifiant' => $idImage]);

                header("Location: ../view/image.php");
                exit();
            }
        }
    } else {

        try {

            // creation demande 
            $stmt = $db->prepare("INSERT INTO demandesuppression (TypeObjet,NomIdentifiant,ValeurIdentifiant,Utilisateur,DateDemande) VALUES (:typeobjet,:nomidentifiant,:valeuridentifiant,:utilisateur,CURDATE()) ");
            if ($stmt->execute(['typeobjet' => 'image', 'nomidentifiant' => 'Id', 'valeuridentifiant' => $idImage, 'utilisateur' => $_SESSION['IdUser']])) {
                header("Location: ../accueil.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Vous avez déja fait une demande pour cette image";
            header("Location: ../view/image.php");
            exit();
        }

    }

}


