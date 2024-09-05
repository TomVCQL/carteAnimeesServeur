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


           <!-- Styles -->
            
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
            overflow-x: hidden;
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
                <h1 style="color:#0d6efd">Modifier Utilisateur</h1>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("SELECT u.* FROM user u WHERE u.Id = :idUser");
                    $stmt->execute(['idUser' => $_POST['user']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <form action="../controller/modifier.php" method="post">
                        <div class="form-floating mb-3">
                            <?php
                            echo "<input type='text' name='nomPrenom' class='form-control' value='" . $user['Nom'] . " " . $user['Prenom'] . "' readonly/>";
                            ?>
                        </div>
                        <div class="form-floating mb-3">
                            <?php echo "<input type='text' name='nom' class='form-control' id='nom' placeholder='Nom parent' value='" . $user['Nom'] . "'/>"; ?>
                            <label class="form-label" for="nom">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <?php echo "<input type='text' name='prenom' class='form-control' id='prenom' placeholder='Prenom enfant' value='" . $user['Prenom'] . "'/>"; ?>
                            <label class="form-label" for="prenom">Prenom</label>
                        </div>
                        <?php
                        if($user['IdRole'] == 2)
                        {?>
                            <div class="form-floating mb-3">
                                <?php
                                echo '<input type="text" class="form-control" id="parent1" name="parent1" placeholder="parent 1" value="' . $user['Parent1'] . '">';
                                ?>
                                <label for="parent 1">Parent 1</label>
                            </div>
                            <div class="form-floating mb-3">
                            <?php
                                echo '<input type="text" class="form-control" id="parent2" name="parent2" placeholder="parent 2" value="' . $user['Parent2'] . '">';
                                ?>
                                <label for="parent2">Parent 2</label>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="form-floating mb-3">
                            <?php echo "<input type='text' name='telephone' class='form-control' id='telephone' placeholder='Telephone' minlength='10' maxlenght='10' value='" . $user['Telephone'] . "'/>"; ?>
                            <label class="form-label" for="telephone">Telephone</label>
                        </div>
                        <div class="form-floating mb-3">
                            <?php echo "<input type='text' name='mail' class='form-control' id='mail' placeholder='Mail' value='" . $user['Mail'] . "'/>"; ?>
                            <label class="form-label" for="mail">Mail</label>
                        </div>
                        <? echo '<input type="hidden" name="id" value="'.$_POST['user'].'">'?>
                        <?php echo '<input type="hidden" name="role" value="'.$user['IdRole'].'">'?>
                        <input type="hidden" name="fonction" value="user">
                        
                        <div class="form-floating mb-3" <?php if ($user['IdRole'] != 2 || $_SESSION['role'] !='admin')  echo 'style="display:none;"'; ?>>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM user WHERE IdRole = :idRole");
                            $stmt->execute(['idRole' => 1]);
                            $rowOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Récupération des séries déjà attribuées
                            $stmt = $db->prepare("SELECT IdOrtho FROM p2o WHERE IdPatient = :idPatient");
                            $stmt->execute(['idPatient' => $_POST['user']]);
                            $tabOrtho = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère directement les IDs en tant que tableau

                             // Déterminer si le select doit être requis
                             if($user['IdRole'] != 2 || $_SESSION['role'] != 'admin'){
                                $isRequired = '';
                             }else{
                                $isRequired = 'required';
                             }
                           
                            


                            // Affichage du select avec les options présélectionnées
                            echo '<select name="ortho[]" id="ortho" class="form-select" multiple size="10" data-placeholder="sélectionner un orthophoniste"' . $isRequired . '>';
                            foreach ($rowOrtho as $orthophoniste) {
                                $selected = in_array($orthophoniste['Id'], $tabOrtho) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($orthophoniste['Id']) . '" ' . $selected . '>' . htmlspecialchars($orthophoniste['Prenom']) . ' ' . htmlspecialchars($orthophoniste['Nom']) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </div>


                        
                        <div class="form-floating mb-3">
                            <?php echo "<input class='btn btn-success' type='submit' name='submit' class='form-control' value='Modifier'/>"; ?>
                        </div>
                    </form>
                    <a href="../view/user.php" ><button class="btn btn-warning">Retour</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>


    $( '#ortho' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    } );


    // Limite d'affichage
    var limite = 10;

    // Sélectionner le tableau
    var tableau = document.getElementById("userTable");

    // Cacher les lignes excédantes
    for (var i = limite; i < tableau.rows.length; i++) {
        tableau.rows[i].style.display = "none";
    }
</script>