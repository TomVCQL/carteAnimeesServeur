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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <h1 style="color:#0d6efd">Liste des séries</h1>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                        // Supprimer le message d'erreur après l'affichage
                        unset($_SESSION['error_message']);
                    }
                    if($_SESSION['IdRole']=='3'){
                        $stmt = $db->prepare("SELECT s.*,u.Prenom,u.Nom FROM serie s, user u  WHERE s.proprietaire = u.Id");
                        $stmt->execute();
                    }else{
                        $stmt = $db->prepare("SELECT s.*,u.Prenom,u.Nom FROM serie s, serietoortho so, user u WHERE so.IdUser = :idUser and so.IdSerie = s.Id and s.proprietaire = u.Id");
                        $stmt->execute(['idUser'=>$_SESSION['IdUser']]);
                    }
            
                
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


                    ?>
                    <table id="myTable" class="table table-borderless table-sm text-center display">
                        <thead>
                            <th>Theme</th>
                            <th>Liste des cartes</th>
                            <th>Description</th>
                            <th>Proprietaire</th>
                            <th><th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($row as $serie) {

                                $style = ($_SESSION['IdRole'] !='3' && $serie['proprietaire'] != $_SESSION['IdUser']) ? 'display: none;' : '';

                                
                                $stmt = $db->prepare("SELECT c.* FROM carte c, cartetoserie cs WHERE cs.IdSerie = :id and cs.IdCarte = c.Id;");
                                $stmt->execute(['id'=>$serie['Id']]);
                                $rowCarte = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                echo "<tr>";
                                echo "<td>" . $serie['Theme'] . "</td>";
                                
                                if (count($rowCarte) > 0) {
                                    echo '<td>
                                            <button class="btn btn-primary  dropdown-toggle boutonSerie" idSerie="'.$serie['Id'].'">
                                                voir les cartes
                                            </button>
                                          </td>';
                                } else {
                                    echo '<td>Il n\'y a pas de carte dans cette série</td>';
                                }
                                echo "<td>" . $serie['description'] . "</td>";
                                echo "<td>" . $serie['Prenom'] ." ".$serie['Nom']. "</td>";
                                echo "<td><form action='../modifier/serie.php' method='post' style='".$style."'><input type='hidden' name='serie' value='".$serie['Id']."' ><input type='hidden' name='proprietaire' value='".$serie['proprietaire']."' ><button type='submit' class='btn btn-primary'>Modifier</button></form></td>";
                                echo "<td><form action='../controller/supprimer.php' method='post' onsubmit='return confirmSubmit(\"" . $serie['Theme'] . "\")' style='".$style."'>
                                    <input type='hidden' name='serie' value='".$serie['Id']."'>
                                    <input type='hidden' name='fonction' value='serie'> 
                                    <button type='submit' class='btn btn-danger'>Supprimer</button>
                                </form>
                                </td>";

                                echo "</tr>";
                                foreach ($rowCarte as $carte)
                                {
                                    echo "<tr class='carteSerie cartes_".$serie['Id']."'>
                                            <td>".$carte['Intitule']."</td>
                                            <td><img src='../image/" . $carte['IdImage'] . ".gif' width='50px'></td>
                                            <td><audio controls style='width:200px;height:50px'>
                                            <source src='../son/" . $carte['IdSon'] . ".mp3' type='audio/mpeg'>
                                                Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                            </audio></td>
                                          </tr>";
                                }

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
        $(".carteSerie").hide();
        $(".boutonSerie").on("click", function(){
            idSerie = this.getAttribute("idSerie");
            if($(".cartes_"+idSerie).is(":visible"))
            {
                $(this).text("voir les cartes");
                $(".cartes_"+idSerie).hide();
            }else{
                $(this).text("cacher les cartes");
                $(".cartes_"+idSerie).show();
            }
        })
    });
    function confirmSubmit(intitule) {
        return confirm("Êtes-vous sûr de vouloir supprimer la serie " + intitule + " ?");
    }


</script>