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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script></html>
   
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
            overflow-y: auto; /* Permet le défilement si le contenu dépasse la hauteur de la barre latérale */
            z-index: 1000; /* Pour s'assurer que la sidebar apparaît au-dessus du contenu */
        }

        .content {
            margin-left: 15%; /* Déplace le contenu vers la droite pour laisser de la place à la sidebar */
            overflow-y: auto; /* Permet le défilement si le contenu dépasse la hauteur maximale */
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
                <h1 style="color:#0d6efd">Modifer une série</h1>
                <div class="card-body">
                <?php
                    $stmt = $db->prepare("SELECT s.* FROM serie s WHERE s.Id = :idSerie");
                    $stmt->execute(['idSerie' => $_POST['serie']]);
                    $serie = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <form class="form-horizontal" action="../controller/modifier.php" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <?php echo '<input type="text" class="form-control" id="theme" name="theme" placeholder="theme" value="'.$serie['Theme'].'"
                                required>' ?>
                            <label for="theme">Theme</label>
                        </div>
                        <div class="form-floating mb-3">
                        <?php echo '<input type="text" class="form-control" id="description" name="description" placeholder="description" value="'.$serie['description'].'"
                                required>' ?>
                            <label for="description">Description</label>
                        </div>

                        <div class="form-floating mb-3">
                            <?php
                            // Prepare the statement to select all users except the current one, who have the role of 'orthophoniste'
                            $stmt = $db->prepare("SELECT * FROM user WHERE id <> :Id AND idRole = 1");
                            $stmt->execute(['Id' => $_POST['proprietaire']]);
                            $rowOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Prepare the statement to select the users already attributed to the series
                            $stmt = $db->prepare("SELECT IdUser FROM serietoortho WHERE IdSerie = :idSerie");
                            $stmt->execute(['idSerie' => $_POST['serie']]);
                            $tabOrtho = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

                            // Create the select element for orthophonistes
                            echo '<select name="orthophoniste[]" id="orthophoniste" class="form-select" multiple size="10" data-placeholder="Sélectionner un orthophoniste (Partage)">';
                            foreach ($rowOrtho as $ortho) {
                                $selected = in_array($ortho['Id'], $tabOrtho) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($ortho['Id']) . '" ' . $selected . '>' . htmlspecialchars($ortho['Prenom']) . ' ' . htmlspecialchars($ortho['Nom']) . '</option>';
                            }
                            echo '</select>';
                            ?>
                            <label for="orthophoniste">Accessibilité Orthophoniste</label>
                        </div>
                        <?php echo '<input type="hidden" name="serie" value="' . $_POST['serie'] . '"/>' ?>
                        <?php echo '<input type="hidden" name="proprietaire" value="' . $_POST['proprietaire'] . '"/>' ?>
                        <input type="hidden" name="fonction" value="serie" />
                        <input type="submit" class="btn btn-primary" value="Modifier" />
                    </form>
                    <a href="../view/serie.php" ><button class="btn btn-warning">Retour</button></a>
                </div>
            </div>

        </div>
    </div>
</body>

<script>

$( '#orthophoniste' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    } );



</script>


</html>