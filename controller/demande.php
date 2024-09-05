<?php

session_start();

if (isset($_POST['fonction']) && $_POST['fonction'] == "creation") {
    creation();
} elseif (isset($_POST['fonction']) && $_POST['fonction'] == "suppression") {
    suppression();
}


function creation()
{
    include('../config/db_config.php');

    $typeDemande = $_POST['typeDemande'];
    $typeObjet = $_POST['typeObjet'];
    $valeurIdentifiant = $_POST['valeurIdentifiant'];
    $numeroDemande = $_POST['numeroDemande'];

    if ($typeDemande == 'valider') {

        $stmt = $db->prepare("UPDATE $typeObjet SET statut = :statut WHERE Id = :id");
        $stmt->execute(['statut' => 'validé', 'id' => $valeurIdentifiant]);
    } else {
        // suppression de l'élément

        $stmt = $db->prepare("DELETE FROM $typeObjet WHERE Id = :id");
        $stmt->execute(['id' => $valeurIdentifiant]);


        // suppression de l'element depuis son emplacement 
        $destination = "../" . $typeObjet . "/" . $valeurIdentifiant;

        $files = glob($destination . '.*');

        if (empty($files)) {
            echo "Erreur : Aucun fichier nommé $valeurIdentifiant avec une extension valide trouvé.";
        } else {
            // Supprimer le premier fichier trouvé
            if (unlink($files[0])) {
                echo "Le fichier " . basename($files[0]) . " a été supprimé avec succès.";
            } else {
                echo "Erreur : Impossible de supprimer le fichier " . basename($files[0]) . ".";
            }
        }
    }

    //suppression de la demande 

    $stmt = $db->prepare("DELETE FROM demandeajout WHERE Id = :id");
    $stmt->execute(['id' => $numeroDemande]);

    header("Location: ../view/creation.php");
    exit();
}

function suppression()
{


    include('../config/db_config.php');

    $typeDemande = $_POST['typeDemande'];
    $typeObjet = $_POST['typeObjet'];
    $valeurIdentifiant = $_POST['valeurIdentifiant'];
    $numeroDemande = $_POST['numeroDemande'];

    if ($typeDemande == 'valider') {

        if ($typeObjet == 'carte') {
            // suppresion serietouser
            $stmt = $db->prepare("DELETE FROM cartetoserie WHERE IdCarte = :id");

            // suppression carte 
            if ($stmt->execute(['id' => $valeurIdentifiant])) {

                $stmt = $db->prepare("  DELETE FROM cartetoortho WHERE IdCarte = :id");
                if ($stmt->execute(['id' => $valeurIdentifiant])) {
                    $stmt = $db->prepare("  DELETE FROM carte WHERE Id = :id");
                    $stmt->execute(['id' => $valeurIdentifiant]);
                }
                
            }
        } elseif ($typeObjet == 'serie') {

            // suppression seriestouser
            $stmt = $db->prepare("  DELETE  FROM serietouser WHERE IdSerie = :id");
            if ($stmt->execute(['id' => $valeurIdentifiant])) {

                // suppression cartetoseries
                $stmt = $db->prepare("  DELETE  FROM cartetoserie WHERE IdSerie = :id");
                if ($stmt->execute(['id' => $valeurIdentifiant])) {

                    //suppression serietoortho
                    $stmt = $db->prepare("  DELETE  FROM serietoortho WHERE IdSerie = :id");
                    if ($stmt->execute(['id' => $valeurIdentifiant])) {
                      
                        // suppression series
                        $stmt = $db->prepare("  DELETE  FROM serie WHERE Id = :id");
                        $stmt->execute(['id' => $valeurIdentifiant]);

                        $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
                        $stmt->execute(['typeobjet' => 'serie', 'valeurIdentifiant' => $valeurIdentifiant]);
                    }

                }
            }
        } elseif ($typeObjet == 'son') {

            // mettre a null les carte qui utilise le son 
            $stmt = $db->prepare("UPDATE carte set IdSon =NULL WHERE IdSon = :id");

            if ($stmt->execute(['id' => $valeurIdentifiant])) {
                // suppression du son 
                $stmt = $db->prepare("DELETE FROM $typeObjet WHERE Id = :id");
                if ($stmt->execute(['id' => $valeurIdentifiant])) {

                    // suppression de l'element depuis son emplacement 
                    $destination = "../son/" . $valeurIdentifiant;

                    $files = glob($destination . '.*');

                    if (empty($files)) {
                        echo "Erreur : Aucun fichier nommé $valeurIdentifiant avec une extension valide trouvé.";
                    } else {
                        // Supprimer le premier fichier trouvé
                        if (unlink($files[0])) {
                            echo "Le fichier " . basename($files[0]) . " a été supprimé avec succès.";
                        } else {
                            echo "Erreur : Impossible de supprimer le fichier " . basename($files[0]) . ".";
                        }
                    }
                }
            }
        } elseif ($typeObjet == 'image') {
            // mettre a null les carte qui utilise le son 
            $stmt = $db->prepare("UPDATE carte set IdImage =NULL WHERE IdImage = :id");

            if ($stmt->execute(['id' => $valeurIdentifiant])) {
                // suppression du son 
                $stmt = $db->prepare("DELETE FROM $typeObjet WHERE Id = :id");
                if ($stmt->execute(['id' => $valeurIdentifiant])) {

                    // suppression de l'element depuis son emplacement 
                    $destination = "../image/" . $valeurIdentifiant;

                    $files = glob($destination . '.*');

                    if (empty($files)) {
                        echo "Erreur : Aucun fichier nommé $valeurIdentifiant avec une extension valide trouvé.";
                    } else {
                        // Supprimer le premier fichier trouvé
                        if (unlink($files[0])) {
                            echo "Le fichier " . basename($files[0]) . " a été supprimé avec succès.";
                        } else {
                            echo "Erreur : Impossible de supprimer le fichier " . basename($files[0]) . ".";
                        }
                    }
                }
            }
        }
    }

    // suppression de la demande et  des duplicat de demande 
    $stmt = $db->prepare("DELETE FROM demandesuppression WHERE TypeObjet = :typeobjet AND valeurIdentifiant = :valeurIdentifiant");
    $stmt->execute(['typeobjet' => $typeObjet, 'valeurIdentifiant' => $valeurIdentifiant]);

    header("Location: ../view/suppression.php");
    exit();
}
