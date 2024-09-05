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
            overflow-y: hidden;
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
                <h1 style="color:#0d6efd">Liste des cartes</h1>
                <div class="card-body">
                    
                    <?php

                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                        // Supprimer le message d'erreur après l'affichage
                        unset($_SESSION['error_message']);
                    }
                    if($_SESSION['IdRole']=='3'){
                        $stmt = $db->prepare("SELECT c.*,u.Prenom,u.Nom FROM carte c, user u WHERE c.proprietaire = u.Id");
                        $stmt->execute();
                    }else{
                        $stmt = $db->prepare("SELECT c.*,u.Prenom,u.Nom FROM carte c, cartetoortho co, user u WHERE co.IdUser = :idUser and c.Id = co.IdCarte and  c.proprietaire = u.Id");
                        $stmt->execute(['idUser'=>$_SESSION['IdUser']]);
                    }
                    
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table id="myTable" class="table table-borderless table-sm text-center display">
                        <thead>
                            <th>Intitule</th>
                            <th>Image</th>
                            <th>Son</th>
                            <th>Descriptions</th>
                            <th>Proprietaire</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $carte) {
                                $style = ($_SESSION['IdRole'] !='3' && $carte['proprietaire'] != $_SESSION['IdUser']) ? 'display: none;' : '';
                                echo "<tr>";
                                echo "<td>" . $carte['Intitule'] . "</td>";
                                echo "<td><img src='../image/" . $carte['IdImage'] . ".gif' width='50px'></td>";
                                echo "<td>
                                        <audio controls style='width:200px;height:50px'>
                                            <source src='../son/" . $carte['IdSon'] . ".mp3' type='audio/mpeg'>
                                                Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                        </audio></td>";

                                echo "<td>" . $carte['description'] . "</td>";
                                echo "<td>" . $carte['Prenom']." ".$carte['Nom']."</td>";

                                echo "<td class='align-middle'>
                                    
                                    <form action='../controller/supprimer.php' method='post' style='float: right;".$style."' onsubmit='return confirmSubmit(\"" . $carte['Intitule'] . "\")' >
                                        <input type='hidden' name='carte' value='" . $carte['Id'] . "' >
                                        <input type='hidden' name='fonction' value='carte'> 

                                        <button class='btn btn-danger' type='submit' style='width: 100px;'>Supprimer</button>
                                    </form>
                                    <form action='../modifier/carte.php' method='post' style='float: right; margin-right: 10px;'>
                                    <input type='hidden' name='carte' value='" . $carte['Id'] . "'>    
                                    <button class='btn btn-primary' type='submit' style='width: 100px;'>Modifier</button>
                                    </form>
                                </td>";

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

    function confirmSubmit(intitule) {
        return confirm("Êtes-vous sûr de vouloir supprimer la carte " + intitule + " ?");
    }
</script>