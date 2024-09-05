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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: hidden;
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
                <h1 style="color:#0d6efd">Choisir Son</h1>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("SELECT * FROM son WHERE  statut <> 'attente'");
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table id="myTable" class="table table-striped table-borderless table-sm text-center display">
                        <thead>
                            <th>Intitule</th>
                            <th>Son</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $son) {
                                echo "<tr>";
                                echo "<td>" . $son['Intitule'] . "</td>";
                                echo "<td>
                                        <audio controls style='width:200px;height:50px'>
                                            <source src='../son/" . $son['Id'] . ".mp3' type='audio/mpeg'>
                                                Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                        </audio></td>";
                                        echo "<td><form action='../modifier/son.php' method='post'><input type='hidden' name='son' value='".$son['Id']."' ><button type='submit' class='btn btn-primary'>Modifier</button></form></td>";
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
    $(document).ready(function () {
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    });
</script>