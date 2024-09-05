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
        include '../create/sidebar.php';
        ?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Modifier Carte</h1>
                <div class="card-body">
                    <?php
                    $stmt = $db->prepare("SELECT c.* FROM carte c WHERE c.Id = :idCarte");
                    $stmt->execute(['idCarte' => $_POST['carte']]);
                    $carte = $stmt->fetch(PDO::FETCH_ASSOC);

                    $style = ($_SESSION['IdRole'] != '3' && $carte['proprietaire'] != $_SESSION['IdUser']) ? 'style="display: none;"' : '';
                    $disable = ($_SESSION['IdRole'] !='3' && $carte['proprietaire'] != $_SESSION['IdUser']) ? 'disabled' : '';

                   
                   ?>
                    <form id="form" class="form-horizontal" action="../controller/modifier.php" method="post">
                        <div class="form-floating mb-3">
                            <?php echo '<input type="text" class="form-control" id="intitule" name="intitule" placeholder="Intitule" value="' . $carte["Intitule"] . '"
                                required '.$disable.'>' ?>
                            <label for="intitule">Intitule</label>
                        </div>
                        <?php
                        $stmt = $db->prepare("SELECT * FROM image WHERE typeImage = 'fictive' AND statut <> 'attente'");
                        $stmt->execute();
                        $rowImage = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="form-floating mb-3" >
                            <select name="image" id="image" class="form-select" <?php echo $disable ?>>
                                <?php
                                foreach ($rowImage as $image) {
                                    if ($image['Id'] == $carte['IdImage']) {
                                        echo '<option value="' . $image['Id'] . '" selected>' . $image['Intitule'] . '</option>';
                                    } else {
                                        echo '<option value="' . $image['Id'] . '">' . $image['Intitule'] . '</option>';
                                    }

                                }
                                ?>
                            </select>
                            <label for="image">Image</label>
                            <img src="" id="miniature" width="200px" />
                        </div>

                        <?php
                        $stmt = $db->prepare("SELECT * FROM image WHERE typeImage = 'reel' AND statut <> 'attente'");
                        $stmt->execute();
                        $rowImage2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="form-floating mb-3">
                            <select name="imageReel" id="imageReel" class="form-select" <?php echo $disable ?>>
                                <?php
                                foreach ($rowImage2 as $image) {
                                    if ($image['Id'] == $carte['IdImageReel']) {
                                        echo '<option value="' . $image['Id'] . '" selected>' . $image['Intitule'] . '</option>';
                                    } else {
                                        echo '<option value="' . $image['Id'] . '">' . $image['Intitule'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="image">Image</label>
                            <img src="" id="miniatureReel" width="200px" />
                        </div>

                        <div class="form-floating mb-3">
                            <?php
                            $stmt = $db->prepare("SELECT * FROM son WHERE statut <> 'attente'");
                            $stmt->execute();
                            $rowSon = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo '  <select name="son" id="son" class="form-control" '.$disable.'>';
                            foreach ($rowSon as $son) {
                                if ($son['Id'] == $carte['IdSon']) {
                                    echo ' <option value="' . $son['Id'] . '" selected>' . $son['Intitule'] . '</option>';
                                } else {
                                    echo ' <option value="' . $son['Id'] . '">' . $son['Intitule'] . '</option>';
                                }

                            }
                            echo '  </select>';
                            ?>
                            <label for="son">Son</label>
                            <audio controls id="audio">
                                <source type='audio/mpeg'>
                                Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                            </audio>
                        </div>
                        

                    
                            <?php
                             echo '<div class="form-floating mb-3" '.$style.'>';
                            $stmt = $db->prepare("SELECT * FROM user WHERE id <> :Id and idRole = 1");
                            $stmt->execute(['Id'=>$carte['proprietaire']]);
                            $rowOrtho = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // récupération partage ortho
                            $stmt = $db->prepare("SELECT idUser FROM cartetoortho WHERE IdCarte = :idCarte and IdUser <> :idUser");
                            $stmt->execute(['idCarte'=>$carte['Id'],'idUser'=>$carte['proprietaire']]);
                            $tabOrtho = $stmt->fetchAll(PDO::FETCH_COLUMN,0);

                            
                            
                            echo '<select name="orthophoniste[]" id="orthophoniste" class="form-select" multiple size="10" data-placeholder="sélectionner un orthophoniste (Partage)" onchange="updateSerieList();" >'; // Ajout de size="10" pour augmenter la taille d'ouverture
                            foreach ($rowOrtho as $ortho) {
                                $selected = in_array($ortho['Id'], $tabOrtho) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($ortho['Id']) . '" ' . $selected . '>' . htmlspecialchars($ortho['Prenom']) .' '. htmlspecialchars($ortho['Nom']). '</option>';
                            }
                            echo '</select>';
                            ?>
                            <label for="orthophoniste">Accessibilité Orthophoniste</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <?php
                            if($_SESSION['IdRole']=='1'){
                                $stmt = $db->prepare("SELECT s.*, u.Prenom,u.Nom FROM serie s, user u WHERE proprietaire = :idUser and s.proprietaire = u.Id");
                                $stmt->execute(['idUser'=>$_SESSION['IdUser']]);
                            }elseif ($_SESSION['IdRole']=='3'){
                                $stmt = $db->prepare("SELECT s.*, u.Prenom,u.Nom  FROM serie s, user u WHERE s.proprietaire = u.Id");
                                $stmt->execute();
                            }
                            $rowSerie = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            // Récupération des séries déjà attribuées
                            if($_SESSION['IdRole']=='1'){
                            $stmt = $db->prepare("SELECT cs.IdSerie FROM cartetoserie cs, cartetoortho co WHERE co.idUser= :idUser and co.IdCarte = :idCarte and co.IdCarte = cs.IdCarte");
                            $stmt->execute(['idCarte' => $_POST['carte'],'idUser'=>$_SESSION['IdUser']]);
                            }elseif ($_SESSION['IdRole']=='3'){
                                $stmt = $db->prepare("SELECT IdSerie FROM cartetoserie WHERE IdCarte = :idCarte");
                                $stmt->execute(['idCarte' => $_POST['carte']]);
                            }
                            $tabSerie = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère directement les IDs en tant que tableau
                        
                            // Affichage du select avec les options présélectionnées
                            echo '<select name="serie[]" id="serie" class="form-select" multiple size="10" data-placeholder="sélectionner une serie" required>';
                            foreach ($rowSerie as $serie) {
                                $selected = in_array($serie['Id'], $tabSerie) ? 'selected' : '';
                                echo '<option   data-secondary-value="'.htmlspecialchars($serie['proprietaire']).'"  value="' . htmlspecialchars($serie['Id']) . '" ' . $selected .'>' . htmlspecialchars($serie['Theme']) . '  ('.htmlspecialchars($serie['Prenom']).' '.htmlspecialchars($serie['Nom']).')</option>';
                            }
                            echo '</select>';
                            ?>
                            <label for="serie">Serie</label>
                        </div>

                        <div class="form-floating mb-3">
                            <?php echo '<input type="text" class="form-control" id="description" name="description" value="' . $carte['description'] . '"
                                placeholder="Description" '.$disable.'>' ?>
                            <label for="description">Description</label>
                        </div>

                        


                        <?php echo '<input type="hidden" name="carte" value="' . $_POST['carte'] . '"/>' ?>
                        <?php echo '<input type="hidden" id="proprietaire" name="proprietaire" value="' . $carte['proprietaire'] . '"/>' ?>
                        <input type="hidden" name="fonction" value="carte" />
                        <input type="submit" class="btn btn-primary" value="Modifier" />
                    </form>
                    <a href="../view/carte.php"><button class="btn btn-warning">Retour</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Récupérer l'élément de sélection
        var selectBox = document.getElementById("image");
        var selectBoxReel = document.getElementById("imageReel");
        var selectSon = document.getElementById("son");

        var miniature = document.getElementById("miniature");
        var miniatureReel = document.getElementById("miniatureReel");
        var audio = document.getElementById("audio");

        // Récupérer la valeur de l'option sélectionnée
        var selectedValue = selectBox.value;
        var selectedValueReel = selectBoxReel.value;
        var selectedSon = selectSon.value;

        miniature.src = "../image/" + selectedValue + ".gif";
        miniatureReel.src = "../image/" + selectedValueReel + ".gif";
        audio.src = "../son/" + selectedSon + ".mp3";

        updateValue();
        updateValueReel();
        updateSon();

        selectBox.addEventListener("change", updateValue);
        selectBoxReel.addEventListener("change", updateValueReel);
        selectSon.addEventListener("change", updateSon);

        function updateValue() {
            var selectedValue = selectBox.value;
            miniature.src = "../image/" + selectedValue + ".gif";
        }

        function updateValueReel() {
            var selectedValueReel = selectBoxReel.value;
            miniatureReel.src = "../image/" + selectedValueReel + ".gif";
        }

        function updateSon() {
            var selectedSon = selectSon.value;
            audio.src = "../son/" + selectedSon + ".mp3";
        }
    });


$( '#serie' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );


$( '#orthophoniste' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    } );

    $('#form').submit(function(e) {
    $(':disabled').each(function(e) {
        $(this).removeAttr('disabled');
    })
});
let originalOptions = [];
document.addEventListener("DOMContentLoaded", function() {
    let selectElement = document.getElementById("serie");
    let options = selectElement.options;

    // Stocker les options originales dans une variable globale
    for (let i = 0; i < options.length; i++) {
        originalOptions.push({
            value: options[i].value,
            text: options[i].textContent,
            secondaryValue: options[i].getAttribute('data-secondary-value')
        });
    }

    
    updateSerieList();
});

function updateSerieList() {
    let proprietaireValue = document.getElementById("proprietaire").value;
    let orthophonisteValue = document.getElementById("orthophoniste").value;
    let selectElement = document.getElementById("serie");
    let options = selectElement.options;

    // Stocker les options sélectionnées actuellement
    let selectedValues = [];
    for (let i = 0; i < options.length; i++) {
        if (options[i].selected) {
            selectedValues.push(options[i].value);
        }
    }

    // Vider le contenu du select
    selectElement.innerHTML = '';

    // Ajouter les options originales filtrées au select
    for (let i = 0; i < originalOptions.length; i++) {
        let option = document.createElement("option");
        option.value = originalOptions[i].value;
        option.textContent = originalOptions[i].text;
        option.setAttribute('data-secondary-value', originalOptions[i].secondaryValue);

        // Vérifier si cette option était sélectionnée précédemment
        if (selectedValues.includes(originalOptions[i].value)) {
            option.selected = true;
        }

        // Filtrer les options en fonction des valeurs de proprietaireValue et orthophonisteValue
        if (originalOptions[i].secondaryValue === proprietaireValue || originalOptions[i].secondaryValue === orthophonisteValue) {
            selectElement.appendChild(option);
        }
    }

    console.log("changement " + proprietaireValue + " " + orthophonisteValue);
}

</script>