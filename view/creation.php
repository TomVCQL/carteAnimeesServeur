<?php
session_start();
include('../config/db_config.php');
if (!isset($_SESSION['nom']) && !isset($_SESSION['prenom']) && !isset($_SESSION['role'])) {
    header("Location: index.php?erreur=3");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #f2f5fc;
        }

        .sidebar {
            position: fixed;
            overflow-y: auto;
            /* Permet le défilement si le contenu dépasse la hauteur de la barre latérale */
            z-index: 1000;
            /* Pour s'assurer que la sidebar apparaît au-dessus du contenu */
        }

        .content {
            margin-left: 15%;
            /* Déplace le contenu vers la droite pour laisser de la place à la sidebar */
            overflow-y: auto;
            /* Permet le défilement si le contenu dépasse la hauteur maximale */
        }
    </style>
</head>

<body>
    <div class="row">
        <?php
        include '../create/sidebar.php';
        ?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Demande de création</h1>
                <div class="card-body">
                    <?php
                    if($_SESSION['role']=='admin'){
                        $stmt = $db->prepare("SELECT * FROM demandeajout");
                        $stmt->execute();
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }else{
                        $stmt = $db->prepare("SELECT * FROM demandeajout WHERE Utilisateur = :id");
                        $stmt->execute(['id'=>$_SESSION['IdUser']]);
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    ?>
                    <table id="myTable" class="table table-striped table-sm text-center display">
                        <thead>
                            <th>Type</th>
                            <th>Intitule</th>
                            <th>Visuel</th>
                            <th>Demandeur</th>
                            <th>Date demande</th>
                            <?php
                            if($_SESSION['role']=='admin'){
                                echo "<th></th>";
                            }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $element) {
                                echo "<tr>";
                                echo "<td>" . $element['TypeObjet'] . "</td>";
                                // affichage du continuel
                                if ($element['TypeObjet'] == 'image') {
                                    $stmt = $db->prepare("SELECT * FROM image WHERE Id = :id");
                                    $stmt->execute(['id' => $element['ValeurIdentifiant']]);
                                    $image = $stmt->fetch(PDO::FETCH_ASSOC);

                                    echo "<td> Intitule: " . $image['Intitule'] . '<br> Type Image: ' . $image['typeImage'] . "</td>";
                                    echo "<td><img src='../image/" . $image['Id'] . ".gif' width='200px'></td>";
                                } elseif ($element['TypeObjet'] == 'son') {

                                    $stmt = $db->prepare("SELECT * FROM son WHERE Id = :id");
                                    $stmt->execute(['id' => $element['ValeurIdentifiant']]);
                                    $son = $stmt->fetch(PDO::FETCH_ASSOC);

                                    echo "<td>" . $son['Intitule'] . "</td>";
                                    echo "<td>
                                            <audio controls style='width:200px;height:50px'>
                                                <source src='../son/" . $son['Id'] . ".mp3' type='audio/mpeg'>
                                                    Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                            </audio></td>";
                                }
                                // affichage utilisateur demandeur
                                $stmt = $db->prepare("SELECT Prenom,Nom FROM user WHERE Id = :id");
                                $stmt->execute(['id' => $element['Utilisateur']]);
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo "<td>" . $user['Prenom'] . ' ' . $user['Nom'] . "</td>";
                                // affichage date de demande
                                $date = new DateTime($element['DateDemande']);
                                $formattedDate = $date->format('d-m-Y');
                                echo "<td>" . $formattedDate . "</td>";

                                if($_SESSION['role']=='admin'){
                                echo "<td class='align-middle'>

                                    <form class='form-horizontal' action='../controller/demande.php' method='post' enctype='multipart/form-data' style='float: left; margin-right: 10px;'>
                                        
                                        <input type='hidden' name='typeDemande' value='valider'/>
                                        <input type='hidden' name='fonction' value='creation' />
                                        <input type='hidden' name='typeObjet' value='" . $element['TypeObjet'] . "' />
                                        <input type='hidden' name='valeurIdentifiant' value='" . $element['ValeurIdentifiant'] . "'/>
                                        <input type='hidden' name='numeroDemande' value='" . $element['Id'] . "'/>

                                        <button class='btn btn-success' type='submit' style='width: 100px;'>Valider</button>
                                    </form>

                                    <form class='form-horizontal' action='../controller/demande.php' method='post' enctype='multipart/form-data' style='float: left;'>
                                        
                                        <input type='hidden' name='typeDemande' value='refuser'/>
                                        <input type='hidden' name='fonction' value='creation' />
                                        <input type='hidden' name='typeObjet' value='" . $element['TypeObjet'] . "' />
                                        <input type='hidden' name='valeurIdentifiant' value='" . $element['ValeurIdentifiant'] . "'/>
                                        <input type='hidden' name='numeroDemande' value='" . $element['Id'] . "'/>

                                        <button class='btn btn-danger' type='submit' style='width: 100px;'>Refuser</button>
                                    </form>
                                </td>";
                                }


                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $('#myTable').DataTable(
                {
                    language: {
                    url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/fr-FR.json',
                },
            }
            );
        });
    });
</script>