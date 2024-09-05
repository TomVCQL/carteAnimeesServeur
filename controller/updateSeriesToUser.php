<?php



$idUser = $_POST['idUser'];





// Inclure le fichier de configuration de la base de données
include('../config/db_config.php');

// Vérifier si $_POST['idSeriesCoches'] est défini
if(isset($_POST['idSeriesCoches'])) {
    $idSeriesCoches = $_POST['idSeriesCoches'];
    // Vérifier si $idSeriesCoches est un tableau non vide
    if(is_array($idSeriesCoches) && !empty($idSeriesCoches)) {
        // Traitement des IdSeries cochés
        foreach ($idSeriesCoches as $idSerieCoche) {
            // Utiliser la connexion à la base de données $db
            $stmt = $db->prepare("INSERT INTO serietouser (idUser, idSerie, idStatut) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
            $stmt->bindParam(2, $idSerieCoche, PDO::PARAM_INT);
            $statut = 1; // Définir le statut à insérer
            $stmt->bindParam(3, $statut, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}





if(isset($_POST['idSeriesNonCoches'])) {
    
    $idSeriesNonCoches = $_POST['idSeriesNonCoches'];
    // Traitement des IdSeries non cochés
    foreach ($idSeriesNonCoches as $idSerieNonCoche) {
    
        $stmt = $db->prepare("DELETE FROM serietouser
        WHERE idUser = :idUser AND idSerie = :idSerie");
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':idSerie', $idSerieNonCoche, PDO::PARAM_INT);
        $stmt->execute();

    }
}


$stmt = $db->prepare("SELECT s.id as id_serie,s.Theme,s.description, su.Id, su.IdSerie, su.IdStatut,su.DateDebut,su.DateFin
                     FROM serie s
                     LEFT OUTER JOIN serietouser su ON s.Id = su.IdSerie
                     AND su.IdUser = :idUser");
$stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
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
        'Id_serie' => $row['id_serie']
        
    ];
}
echo json_encode($series);
?>

