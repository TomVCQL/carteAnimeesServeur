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
                <h1 style="color:#0d6efd">Liste des images</h1>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                        // Supprimer le message d'erreur après l'affichage
                        unset($_SESSION['error_message']);
                    }
                    $stmt = $db->prepare("SELECT * FROM image WHERE statut <> 'attente'");
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table id="myTable" class="table table-borderless table-sm text-center display">
                        <thead>
                            <th>Image</th>
                            <th>Intitule</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $image) {
                                echo "<tr>";
                                echo "<td><img src='../image/" . $image['Id'] . ".gif' width='200px'></td>";
                                echo "<td>" . $image['Intitule'] . "</td>";
                                echo "<td class='align-middle'>
                                    <form action='../modifier/image.php' method='post' style='display: inline-block; margin-right: 10px;float-left'>
                                        <input type='hidden' name='image' value='" . $image['Id'] . "' >
                                        <button type='submit' class='btn btn-primary'>Modifier</button>
                                    </form>
                                    <form action='../controller/supprimer.php' method='post' style='display: inline-block;float-left' onsubmit='return confirmSubmit(\"" . $image['Intitule'] . "\")'>
                                                <input type='hidden' name='image' value='" . $image['Id'] . "'>
                                                <input type='hidden' name='fonction' value='image'>
                                                <button type='submit' class='btn btn-danger'>Supprimer</button>
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
        return confirm("Êtes-vous sûr de vouloir supprimer l'image " + intitule + " ?");
    }
</script>