<?php
session_start();
include ('../config/db_config.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            /* background-repeat: no-repeat;
            background-size: cover;
            background-image: url("../background.jpg"); */
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
                <h1 style="color:#0d6efd">Modifier Carte</h1>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("SELECT c.* FROM carte c WHERE 1");
                    $stmt->execute();
                    $rowCarte = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                        <div class="form-floating mb-3">
                            <table id="myTable" class="table table-hover table-sm text-center display">
                                <thead>
                                    <th>Intitule</th>
                                    <th>Image</th>
                                    <th>Son</th>
                                    
                                    <th>Descriptions</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($rowCarte as $carte) {
                                        echo "<tr>";
                                        echo "<td>" . $carte['Intitule'] . "</td>";
                                        echo "<td><img src='../image/" . $carte['IdImage'] . ".gif' width='50px'></td>";
                                        echo "<td>
                                        <audio controls style='width:200px;height:50px'>
                                            <source src='../son/" . $carte['IdSon'] . ".mp3' type='audio/mpeg'>
                                                Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                        </audio></td>";
                                        
                                        echo "<td>" . $carte['description'] . "</td>";
                                        echo "<td><form action='../modifier/carte.php' method='post'><input type='hidden' name='carte' value='".$carte['Id']."' ><button type='submit' class='btn btn-primary'>Modifier</button></form></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    // Limite d'affichage
    var limite = 10;

    // Sélectionner le tableau
    var tableau = document.getElementById("userTable");

    // Cacher les lignes excédantes
    for (var i = limite; i < tableau.rows.length; i++) {
        tableau.rows[i].style.display = "none";
    }
</script>