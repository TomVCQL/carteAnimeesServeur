<?php
if (isset($_GET['fonction'])) {
    callFunction($_GET['fonction']);
}

function getUser()
{
    include('config/db_config.php');

    if (!isset($_GET["identifiant"]) || !isset($_GET["mdp"])) {
        echo json_encode(array("success" => false, "message" => "Identifiant ou mot de passe manquant."));
        return;
    }

    $email = $_GET["identifiant"];
    $password = $_GET["mdp"];

    $stmt = $db->prepare("SELECT password FROM user WHERE Mail = :mail");
    $stmt->execute(['mail'=>$email]);

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $row["password"];

        if (password_verify($_GET["mdp"], $hashed_password)) {
            
            $stmt = $db->prepare("SELECT Id, Nom, Prenom, IdRole FROM user WHERE Mail = :mail");
            $stmt->execute(['mail'=>$email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            
            $stmt = $db->prepare("SELECT NomRole FROM role WHERE id = :id");
            $stmt->execute(['id'=>$row["IdRole"]]);
            $rowRole = $stmt->fetch(PDO::FETCH_ASSOC);

            
            echo json_encode(array("success" => true, "user" => $row, "role" => $rowRole));
        } else {
            echo json_encode(array("success" => false, "message" => "Mot de passe incorrect."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Identifiant incorrect."));
    }
}

function getUserSeries()
{
    include('config/db_config.php');
    if (!isset($_GET["id"])) {
        echo json_encode(array("success" => false, "message" => "Id user manquant"));
        return;
    }

    $stmt = $db->prepare("  SELECT stu.IdSerie, stu.IdStatut, s.Theme, stu.IdLastCard,
                            (SELECT COUNT(*) FROM cartetoserie cs where cs.IdSerie = s.Id) AS NbCartes, stu.id
                            FROM serietouser stu,serie s
                            WHERE stu.IdUser = :id and stu.IdSerie = s.id");
    $stmt->execute(['id'=>$_GET['id']]);
    $rowSeries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($rowSeries) < 1)
    {
        echo json_encode(array("success" => false, "message" => "il n'y a pas de série pour cette utilisateur"));
    }
    else{
        echo json_encode(array("success" => true, "series" => $rowSeries));
    }
    
}
function getCartes()
{
    include('config/db_config.php');
    if (!isset($_GET["idSerie"])) {
        echo json_encode(array("success" => false, "message" => "Id serie manquant"));
        return;
    }

    $stmt = $db->prepare("  SELECT c.*,cs.IdSerie
                            FROM carte c , cartetoserie cs
                            WHERE cs.IdSerie = :IdSerie and c.Id = cs.IdCarte");
    $stmt->execute(['IdSerie'=>$_GET['idSerie']]);
    $rowCartes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($rowCartes) < 1)
    {
        echo json_encode(array("success" => false, "message" => "il n'y a pas de carte pour cette série"));
    }
    else{
        echo json_encode(array("success" => true, "cartes" => $rowCartes));
    }
    
}

function setCarteTerminer()
{
    include('config/db_config.php');

    if (!isset($_GET["idSerie"])) {
        echo json_encode(array("success" => false, "message" => "Id serie manquant"));
        return;
    }
    elseif (!isset($_GET["idUser"])) {
        echo json_encode(array("success" => false, "message" => "Id user manquant"));
        return;
    }

    $stmt = $db->prepare("  UPDATE serietouser
                            SET IdStatut = 3
                            WHERE IdSerie = :IdSerie
                            AND IdUser = :IdUser");

    if($stmt->execute(['IdSerie'=>$_GET['idSerie'],'IdUser'=>$_GET['idUser']]))
    {
        echo json_encode(array("success" => true, "message" => "OK"));
    }
    else{
        echo json_encode(array("success" => false, "message" => "problème lors de la modification de serietouser"));
    }
    
}

function setIdLastCard()
{
    
    include('config/db_config.php');

    if (!isset($_GET["idSerie"])) {
        echo json_encode(array("success" => false, "message" => "Id serie manquant"));
        return;
    }
    elseif (!isset($_GET["idUser"])) {
        echo json_encode(array("success" => false, "message" => "Id user manquant"));
        return;
    }
    elseif (!isset($_GET["idCarte"]))
    {
        echo json_encode(array("success" => false, "message" => "Id carte manquant"));
        return;
    }

    $stmt = $db->prepare("  UPDATE serietouser
                            SET IdLastCard = :IdCarte
                            WHERE IdSerie = :IdSerie
                            AND IdUser = :IdUser");

    if($stmt->execute(['IdSerie'=>$_GET['idSerie'],'IdUser'=>$_GET['idUser'],'IdCarte'=>$_GET["idCarte"]]))
    {
        echo json_encode(array("success" => true, "message" => "OK"));
    }
    else{
        echo json_encode(array("success" => false, "message" => "problème lors de la modification de serietouser"));
    }
}


function getPositionCard()
{
    include('config/db_config.php');

    if (!isset($_GET["idSerie"])) {
        echo json_encode(array("success" => false, "message" => "Id serie manquant"));
        return;
    } elseif (!isset($_GET["idLastCard"])) {
        echo json_encode(array("success" => false, "message" => "IdLastCard manquant"));
        return;
    }

    $stmt = $db->prepare("SELECT position
                            FROM (
                                SELECT 
                                    c.id AS idcarte,
                                    @rownum := @rownum + 1 AS position
                                FROM 
                                    serie s, carte c, cartetoserie cs, (SELECT @rownum := 0) r
                                WHERE 
                                    s.Id = cs.IdSerie  and c.Id = cs.IdCarte AND s.Id = :IdSerie
                                ) AS numbered_rows
                            WHERE 
                                idcarte = :IdLastCard");

    $stmt->execute(['IdSerie'=>$_GET['idSerie'], 'IdLastCard'=>$_GET['idLastCard']]);
    $numberCartes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($numberCartes) < 1)
    {
        echo json_encode(array("success" => false, "message" => "erreur lors du calcul de la position"));
    }
    else{
        echo json_encode(array("success" => true, "cartes" => $numberCartes));
    }

}

function callFunction($function)
{
    $function();
}
