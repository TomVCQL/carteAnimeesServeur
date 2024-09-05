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

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto;
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
        include 'sidebar.php';
        $idRole = 1;
        $sql = "Select * FROM user WHERE IdRole = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $orthos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Créer un Utilisateur</h1>
                <div class="card-body">
                    <form class="form-horizontal" action="../controller/create.php" method="post" name="userForm">
                        <div class="form-floating mb-3">
                            <select name="role" id="role" class="form-control">
                                <?php
                                if ($_SESSION['role'] == "admin") {
                                    echo '<option value="1">Orthophoniste</option>
                                    <option value="2">Patient</option>
                                    <option value="3">Admin</option>';
                                } else {
                                    echo '<option value="2">Patient</option>';
                                }
                                ?>
                            </select>
                            <label for="role">Role</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="nom" required>
                            <label for="nom">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom"
                                required>
                            <label for="prenom">Prénom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="parent1" name="parent1" placeholder="parent 1"
                                >
                            <label for="parent 1">Parent 1</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="parent2" name="parent2" placeholder="parent 2"
                                >
                            <label for="parent2">Parent 2</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email"
                                required>
                            <label for="email">Adresse Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="mot de passe" required>
                            <label for="password">Mot de passe</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                placeholder="telephone" maxlength="10" minlength="10" required>
                            <label for="telephone">Téléphone</label>
                        </div>
                        <?php
                        if ($_SESSION['role'] == "admin") { ?>
                            <div class="form-floating mb-3" id="divOrtho">
                            <?php
                            // Affichage du select avec les options présélectionnées
                            echo '<select name="ortho[]" id="ortho" class="form-select" multiple size="10" data-placeholder="sélectionner un orthophoniste">';
                            foreach ($orthos as $ortho) {
                                echo '<option value="' . htmlspecialchars($ortho['Id']) . '">' . htmlspecialchars($ortho['Nom']) . " ".htmlspecialchars($ortho['Prenom']).'</option>';
                            }
                            echo '</select>';
                            ?>
                        </div>
                        <?php }
                        ?>
                        <input type="hidden" name="fonction" value="user" />
                        <input type="submit" class="btn btn-primary" value="Créer" />
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var selectBox = document.getElementById("role");
        updateValue();

        selectBox.addEventListener("change", updateValue);
        function updateValue() {
            parent1 = document.getElementById("parent1");
            parent2 = document.getElementById("parent2");
            divOrtho = document.getElementById("divOrtho");

            var selectedValue = selectBox.value;
            if (selectedValue == 2) {
                parent1.style.display = "block";
                parent2.style.display = "block";
                divOrtho.style.display = "block";
                parent1.setAttribute("required", "required");
                parent2.setAttribute("required", "required");
                ortho.setAttribute("required", "required");
            }
            else {
                parent1.style.display = "none";
                parent2.style.display = "none";
                divOrtho.style.display = "none";
                parent1.removeAttribute("required");
                parent2.removeAttribute("required");
                ortho.removeAttribute("required");
            }
        }

    $( '#ortho' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    } );

    });
    
</script>