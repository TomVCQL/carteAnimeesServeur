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
            background-color: #f2f5fc;
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
        include 'sidebar.php';
        ?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Créer une série</h1>
                <div class="card-body">
                    <form class="form-horizontal" action="../controller/create.php" method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="theme" name="theme" placeholder="theme"
                                required>
                            <label for="theme">Theme</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="description" name="description" placeholder="description"
                                required>
                            <label for="description">Description</label>
                        </div>
                        <?php
                       
                        echo '<div class="form-floating mb-3">';
                                                    
                        // Supposons que vous stockez le rôle de l'utilisateur dans $_SESSION['userRole']
                        $isAdmin = ($_SESSION['IdRole'] == '3');
                        
                        if ($isAdmin) {
                            // Si l'utilisateur est un admin, afficher le champ de sélection des propriétaires
                            $stmt = $db->prepare("SELECT * FROM user WHERE IdRole = 1");
                            $stmt->execute();
                            $tabProprio = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            echo '<select name="proprietaire" id="proprietaire" class="form-select" onchange="updateSelect()" required >';
                            foreach ($tabProprio as $proprio) {
                                echo '<option value="' . htmlspecialchars($proprio['Id']) . '">' . htmlspecialchars($proprio['Prenom']) . ' ' . htmlspecialchars($proprio['Nom']) . '</option>';
                            }
                            echo '</select>';
                            echo '<label for="proprietaire">Proprietaire</label>';
                        
                        } else {
                            // Si l'utilisateur n'est pas un admin, définir automatiquement le propriétaire à l'utilisateur connecté
                            echo '<input type="hidden" name="proprietaire" value="' . htmlspecialchars($_SESSION['IdUser']) . '">';
                        }
                        
                    
                        echo '</div>';
                        echo '<div class="form-floating mb-3">';
                        
                        $stmt = $db->prepare("SELECT * FROM user WHERE Id <> :id and idRole = 1");
                        $stmt->execute(['id'=>$_SESSION['IdUser']]);
                        $rowOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo '<select name="orthophoniste[]" id="orthophoniste" class="form-select" multiple size="10" data-placeholder="sélectionner un orthophoniste (Partage)">'; // Ajout de size="10" pour augmenter la taille d'ouverture
                        foreach ($rowOrtho as $ortho) {
                            echo '<option value="' . htmlspecialchars($ortho['Id']) . '">' . htmlspecialchars($ortho['Prenom']) .' '. htmlspecialchars($ortho['Nom']). '</option>';
                        }
                        echo '</select>';
                        
                        echo '</div>';
                        ?>
                       
                        <input type="hidden" name="fonction" value="serie" />
                        <input type="submit" class="btn btn-primary" value="Créer" />
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>

</html>


<script>
   
   document.addEventListener('DOMContentLoaded', function() {
        updateSelect();
    });
   function updateSelect() {
        // Récupérer la valeur sélectionnée du premier select
        let selectedValue = document.getElementById("proprietaire").value;

        // Réinitialiser le contenu du deuxième select
        let select2 = document.getElementById("orthophoniste");
        select2.innerHTML = '';

        // Copier toutes les options du premier select à l'exception de l'option sélectionnée
        let optionsSelect1 = document.getElementById("proprietaire").options;
        for (let i = 0; i < optionsSelect1.length; i++) {
            if (optionsSelect1[i].value !== selectedValue) {
                let option = document.createElement("option");
                option.value = optionsSelect1[i].value;
                option.textContent = optionsSelect1[i].textContent;
                select2.appendChild(option);
            }
        }
    }


  $( '#orthophoniste' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    } );




</script>