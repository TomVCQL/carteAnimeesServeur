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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
            background-color: #f2f5fc;
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
        <?php include 'sidebar.php';?>
        <div class="col-md-10 content">
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Attribuer une série</h1>
                <div class="card-body">
                <?php   
    
                    if ($_SESSION['role'] == "admin") {
                        $stmt = $db->prepare("SELECT u.id, u.Prenom, u.Nom FROM user u WHERE u.idRole = 2");
                        $stmt->execute();
                        $rowUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $stmt = $db->prepare("SELECT u.id, u.Prenom, u.Nom FROM p2o p, user u WHERE u.idRole = 2 AND p.IdOrtho = " . $_SESSION['IdUser'] . " AND p.IdPatient = u.id;");
                        $stmt->execute();
                        $rowUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }

                    $stmt = $db->prepare("SELECT id,NonStatut FROM statut");
                    $stmt->execute();
                    $listeStatut = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $listeStatutJSON = json_encode($listeStatut);

                ?>

                <div class="form-floating mb-3">
                    <select class="form-select" name="utilisateur" id="utilisateur">
                        <?php foreach ($rowUser as $user): ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo $user['Prenom'] . ' ' . $user['Nom']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="utilisateur">Patient</label>
                </div>
            </div>

            </div>
            <div class="card m-3 text-center">
                <h1 style="color:#0d6efd">Série Attribué</h1>
                <div class="card-body">
                  
                <table class="table" id="formulaires-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Theme</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date début</th>
                                    <th scope="col">Date fin</th>
                                    <th scope="col">Statut</th>
                                </tr>
                            </thead>
                            <tbody>

                            
                            </tbody>
                     </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>



function remplirTableau(series, listeStatut) {
    var listeStatut = <?php echo $listeStatutJSON; ?>;
    // Sélectionne le tbody de la table
    var tbody = document.querySelector('#formulaires-table tbody');
    // Vide le contenu actuel du tbody
    tbody.innerHTML = '';

    // Boucle à travers les séries et ajoute chaque série à la table
    series.forEach(function(serie, index) {
        // Crée une nouvelle ligne de tableau
        var row = document.createElement('tr');

        // Remplir le menu déroulant avec les options de statut
        var selectHTML = '';

        listeStatut.forEach(function(statut) {
            selectHTML += `<option value="${statut.id}" ${serie.IdStatut == statut.id ? 'selected' : ''}>${statut.NonStatut}</option>`;
        });

        // Ajoute le contenu HTML à la ligne de tableau
        row.innerHTML = `
            <td style="text-align: center;">
                <input class="form-check-input" type="checkbox" value="${serie.Id_serie}" id="flexCheckDefault_${index}" ${serie.IdSerie ? 'checked' : ''}>
            </td>
            <td>${serie.Theme} (${serie.Prenom} ${serie.Nom})</td>
            <td>${serie.Description}</td>
            <td>${serie.DateDebut}</td>
            <td>${serie.DateFin}</td>
            <td>
                <select class="form-control">${selectHTML}</select>
            </td>
        `;
        
        // Ajoute la ligne au tbody
        tbody.appendChild(row);
        
        // Ajoute un gestionnaire d'événements pour la case à cocher
        var checkbox = row.querySelector('.form-check-input');
        var serieId = serie.Id_serie;
        
        var select = document.getElementById('utilisateur');
        var selectedOption = select.options[select.selectedIndex];
        var id = selectedOption.value;
        
        checkbox.addEventListener('change', function() {
            var statutSelect = row.querySelector('.form-control').value;
            if (checkbox.checked) {
                console.log("La case " + serie.Id_serie + " cochée est cochée. Statut sélectionné : " + statutSelect);
                $.ajax({
                    url: '/PFE/carteAnimees/controller/insertSerieToUser.php',
                    method: 'POST',
                    data: { idUser: id, idSerie: serie.Id_serie ,idStatut: statutSelect},
                    success: function(response) {
                        // Gérer la réponse de la requête AJAX ici
                        var series = JSON.parse(response);
                        remplirTableau(series);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de l\'envoi des données:', error);
                    }
                });
            } else {
                console.log("La case " + serie.Id_serie+ " idtoseries :"+serie.Id) ;
                $.ajax({
                    url: '/PFE/carteAnimees/controller/deleteSerieToUser.php',
                    method: 'POST',
                    data: { idUser: id, idSerie: serie.Id},
                    success: function(response) {
                        // Gérer la réponse de la requête AJAX ici
                        var series = JSON.parse(response); // Convertir la réponse JSON en objet JavaScript
                        remplirTableau(series);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de l\'envoi des données:', error);
                    }
                });

            }
        });

        // Ajoute un gestionnaire d'événements pour le changement de statut dans la liste déroulante
        
        var selectElement = row.querySelector('.form-control');
            selectElement.addEventListener('change', function() {
                // Récupérez l'ID de la nouvelle valeur sélectionnée dans la liste déroulante
                var nouvelIdStatut = this.value;
                
                var select = document.getElementById('utilisateur');
                var selectedOption = select.options[select.selectedIndex];
                var id = selectedOption.value;

                // Vérifiez si serie.Id est défini avant d'envoyer la requête AJAX
                if (typeof serie.Id !== 'undefined' && serie.Id !== null) {
                    $.ajax({
                        url: '/PFE/carteAnimees/controller/updateSerieToUser.php',
                        method: 'POST',
                        data: { idStatut: nouvelIdStatut, idSerie: serie.Id, idUser: id },
                        success: function(response) {
                            // Gérer la réponse de la requête AJAX ici
                            var series = JSON.parse(response); // Convertir la réponse JSON en objet JavaScript
                            remplirTableau(series);
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur lors de l\'envoi des données:', error);
                        }
                    });
                }
            });

    });
}


// Appel de la fonction remplirTableau avec les séries et la liste des statuts
// remplirTableau(series, listeStatut);


// Attend que le document soit prêt
document.addEventListener("DOMContentLoaded", function() {
    // Obtient l'ID du premier élément de la liste
    var select = document.getElementById('utilisateur');
    var id = select.options[0].value; // Le premier élément a l'indice 0
    
    // Envoi des données au serveur via AJAX
    $.ajax({
        url: '/PFE/carteAnimees/controller/ajax.php',
        method: 'POST',
        data: { idUser: id },
        success: function(response) {
            // Gérer la réponse de la requête AJAX ici
            var series = JSON.parse(response); // Convertir la réponse JSON en objet JavaScript
            remplirTableau(series);
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs ici
            console.error(xhr.responseText);
        }
    });

    // Ajoute un écouteur d'événement pour le changement de sélection
    document.getElementById('utilisateur').addEventListener('change', function() {
        var select = document.getElementById('utilisateur');
        var selectedOption = select.options[select.selectedIndex];
        var id = selectedOption.value;
        
        // Envoi des données au serveur via AJAX
        $.ajax({
            url: '/PFE/carteAnimees/controller/ajax.php',
            method: 'POST',
            data: { idUser: id },
            success: function(response) {
                // Gérer la réponse de la requête AJAX ici
                var series = JSON.parse(response); // Convertir la réponse JSON en objet JavaScript
                remplirTableau(series);
            },
            error: function(xhr, status, error) {
                // Gérer les erreurs ici
                console.error(xhr.responseText);
            }
        });
    });
});

function detecterClic() {
    // Sélectionne l'élément input
    var input = document.getElementById('flexCheckDefault');

    // Ajoute un écouteur d'événements pour le clic sur l'input
    input.addEventListener('click', function() {
        // Vérifie si l'input est coché ou non
        if (input.checked) {
            console.log("L'input est coché.");
        } else {
            console.log("L'input n'est pas coché.");
        }
    });
}


</script>
