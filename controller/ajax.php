<?php
session_start();
include('../config/db_config.php');

$idUser = $_POST['idUser'];

if($_SESSION['IdRole']=='3'){
    $stmt = $db->prepare("SELECT s.id as id_serie,s.Theme,s.description, su.Id, su.IdSerie, su.IdStatut,su.DateDebut,su.DateFin,u.Prenom,u.Nom
    FROM serie s
    INNER JOIN user u ON s.proprietaire = u.Id
    LEFT OUTER JOIN serietouser su ON s.Id = su.IdSerie
    AND su.IdUser = :idUser ORDER by s.Theme ASC; ");
    $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
}else{
    $stmt = $db->prepare("SELECT s.id as id_serie,s.Theme,s.description, su.Id, su.IdSerie, su.IdStatut,su.DateDebut,su.DateFin,u.Prenom,u.Nom
    FROM serie s 
    INNER JOIN serietoortho so ON so.IdUser = :idOrtho and s.Id = so.IdSerie  
    LEFT join serietouser su ON so.IdSerie = su.IdSerie and su.IdUser = :idUser
    LEFT JOIN user u ON s.proprietaire = u.Id
    ORDER by s.Theme ASC;");
    $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $stmt->bindParam(':idOrtho',$_SESSION['IdUser'], PDO::PARAM_INT);
}

$stmt->execute();

$series = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Vérifier si le champ 'materiel' est null et le remplacer par une chaîne vide si nécessaire
    $series[] = [
        'Id' => $row['Id'], // Utilisation de 'Id' au lieu de 'id' pour correspondre à la casse dans le HTML
        'Theme' => $row['Theme']  ?? '',
        'Description' => $row['description']  ?? '', // Utilisation de 'description' au lieu de 'Description' pour correspondre à la casse dans la base de données
        'IdSerie' => $row['IdSerie'],
        'IdStatut' => $row['IdStatut']  ?? '',
        'DateDebut' => $row['DateDebut']  ?? '',
        'DateFin' => $row['DateFin']  ?? '',
        'Id_serie' => $row['id_serie'],
        'Prenom'=>$row['Prenom'],
        'Nom'=>$row['Nom']
        
    ];
}


echo json_encode($series);
?>