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
            <h1 style="color:#0d6efd">Liste des utilisateurs</h1>
                <div class="card-body text-center">
                    <?php
                    if ($_SESSION['role'] == "admin")
                    {
                        $sql = "SELECT u.*, r.NomRole FROM user u, role r WHERE u.IdRole = r.Id";
                    }
                    else
                    {
                        $sql = "SELECT u.*, r.NomRole FROM `p2o` p, user u, role r WHERE IdOrtho = ".$_SESSION['IdUser']." AND p.IdPatient = u.id AND r.Id = u.IdRole";
                    }
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <table id="myTable" class="table table-borderless table-sm text-center">
                        <thead>
                            <th>Nom Parent</th>
                            <th>Prenom Enfant</th>
                            <th>Prenom parent 1</th>
                            <th>Prenom parent 2</th>
                            <th>Télephone</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $user)
                            {
                                echo "<tr>";
                                echo "<td>".$user['Nom']."</td>";
                                echo "<td>".$user['Prenom']."</td>";
                                echo "<td>".$user['Parent1']."</td>";
                                echo "<td>".$user['Parent2']."</td>";
                                echo "<td>".$user['Telephone']."</td>";
                                echo "<td>".$user['Mail']."</td>";
                                echo "<td>".$user['NomRole']."</td>";
                                echo "<td><div class='d-flex justify-content-center'><form action='../modifier/user.php' method='post'><input type='hidden' name='user' value='".$user['Id']."' ><button type='submit' class='btn btn-primary'>Modifier</button></form>";
                                echo "&nbsp<form action='../controller/supprimer.php' method='post' onsubmit='return confirmSubmit(\"" . $user['Nom'] . "\", \"" . $user['Prenom'] . "\");'><input type='hidden' name='user' value='".$user['Id']."' ><input type='hidden' name='fonction' value='user' ><button type='submit' class='btn btn-danger'>Supprimer</button></form></div></td>";
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
    $('#myTable').DataTable(
        {
            language: {
            url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/fr-FR.json',
        },
        }
    );
});
function confirmSubmit(nom, prenom) {
            return confirm("Êtes-vous sûr de vouloir supprimer cette utilisateur : " + nom + " " + prenom +"?");
        }
</script>