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
        <?php include '../create/sidebar.php'; ?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Modifier son</h1>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("SELECT * FROM son WHERE id = :id");
                    $stmt->execute(['id'=>$_POST['son']]);
                    $serie = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div id="alert-container"></div>
                    <form class="form-horizontal" action="../controller/modifier.php" method="post" enctype="multipart/form-data" onsubmit="return validateAudio(event)">
                        <div class="form-floating mb-3">
                            <?php echo '<input type="text" class="form-control" id="intitule" name="intitule" placeholder="intitule" value="'.$serie['Intitule'].'" required>'; ?>
                            <label for="intitule">Intitule</label>
                        </div>
                        <div class="mb-3">
                            <?php echo '<input type="file" class="form-control" id="fichier" name="fichier" placeholder="fichier" value="'.$_POST['son'].'">'; ?>
                        </div>
                        <?php echo '<input type="hidden" name="son" value="'.$_POST['son'].'"/>'?>
                        <input type="hidden" name="fonction" value="son" />
                        <input type="submit" class="btn btn-primary" value="Modifier" />
                    </form>
                    <a href="../view/son.php"><button class="btn btn-warning">Retour</button></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateAudio(event) {
            event.preventDefault(); // Empêcher la soumission par défaut du formulaire

            const maxDuration = 2; // Durée maximale en secondes
            const fileInput = document.getElementById('fichier');
            const file = fileInput.files[0];
            const alertContainer = document.getElementById('alert-container');

            if (file) {
                const audio = new Audio();
                audio.src = URL.createObjectURL(file);

                audio.onloadedmetadata = function() {
                    const duration = audio.duration;
                    URL.revokeObjectURL(audio.src);

                    if (duration > maxDuration) {
                        alertContainer.innerHTML = '<div class="alert alert-danger" role="alert">La durée du fichier audio ne doit pas dépasser 2 secondes.</div>';
                    } else {
                        alertContainer.innerHTML = ''; // Effacer les alertes précédentes
                        document.querySelector('form').submit(); // Soumettre le formulaire
                    }
                };
            }
            else
            {
                alertContainer.innerHTML = '<div class="alert alert-danger" role="alert">Veuillez choisir un fichier audio pour modifier</div>';
            }
        }
    </script>
</body>


</html>