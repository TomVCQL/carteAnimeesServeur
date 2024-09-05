<?php
session_start();
include('config/db_config.php');
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
    <title>Accueil</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <style>
        html,
        body {
            height: 100%;
            overflow-x: hidden;
            background-color: #f2f5fc;
            /* background-repeat: no-repeat;
            background-size: cover; */
            /* background-image: url("background.jpg"); */
        }

        .sidebar {
            position: fixed;
            overflow-y: auto;
            /* Permet le défilement si le contenu dépasse la hauteur de la barre latérale */
            z-index: 1000;
            /* Pour s'assurer que la sidebar apparaît au-dessus du contenu */
        }

        .content {
            margin-left: 13%;
            /* Déplace le contenu vers la droite pour laisser de la place à la sidebar */
            overflow-y: auto;
            /* Permet le défilement si le contenu dépasse la hauteur maximale */
        }

        .fc-timegrid-slot {
            background-color: #ffffff;
            height: 3em;
            /* Définir le fond du jour en blanc */
        }

        .fc-col-header-cell-cushion {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="row">
        <?php
        include 'create/sidebar.php';
        ?>
        <div class="col-md-10 content">
            <div class="row m-4">
                <span>Bonjour, <br>
                    <h3><b>
                            <?php echo ucfirst($_SESSION['prenom']) . " " . ucfirst($_SESSION['nom']) ?>
                        </b></h3>
                </span>
            </div>
            <div class="row m-3">
                <div class="col-md-6">
                    <div class="row m-1">
                        <div class="card" style="border-radius:10px;">
                            <h5 class="p-2"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                                    <path
                                        d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                    <path
                                        d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z" />
                                </svg> Patients</h5>
                            <div class="card-body text-center">
                                <table class="table table-borderless" id="userTable">
                                    <thead>
                                        <th>Nom</th>
                                        <th>Prenom</th>
                                        <th>Parent 1</th>
                                        <th>Parent 2</th>
                                        <th>Telephone</th>
                                        <th>Mail</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $IdRole = 2;
                                        $stmt = $db->prepare("SELECT * FROM user WHERE IdRole = :idRole");
                                        $stmt->execute(['idRole' => $IdRole]);
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($row as $user) {
                                            echo
                                                "<tr>
                                            <td>" . $user['Nom'] . "</td>
                                            <td>" . $user['Prenom'] . "</td>
                                            <td>" . $user['Parent1'] . "</td>
                                            <td>" . $user['Parent2'] . "</td>
                                            <td>" . $user['Telephone'] . "</td>
                                            <td>" . $user['Mail'] . "</td>
                                        </tr>";

                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="row m-1">
                        <div class="card" style="border-radius:10px;">
                            <h5 class="p-2"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-filetype-gif" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M14 4.5V14a2 2 0 0 1-2 2H9v-1h3a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM3.278 13.124a1.4 1.4 0 0 0-.14-.492 1.3 1.3 0 0 0-.314-.407 1.5 1.5 0 0 0-.48-.275 1.9 1.9 0 0 0-.636-.1q-.542 0-.926.229a1.5 1.5 0 0 0-.583.632 2.1 2.1 0 0 0-.199.95v.506q0 .408.105.745.105.336.32.58.213.243.533.377.323.132.753.132.402 0 .697-.111a1.29 1.29 0 0 0 .788-.77q.097-.261.097-.551v-.797H1.717v.589h.823v.255q0 .199-.09.363a.67.67 0 0 1-.273.264 1 1 0 0 1-.457.096.87.87 0 0 1-.519-.146.9.9 0 0 1-.305-.413 1.8 1.8 0 0 1-.096-.615v-.499q0-.547.234-.85.237-.3.665-.301a1 1 0 0 1 .3.044q.136.044.236.126a.7.7 0 0 1 .17.19.8.8 0 0 1 .097.25zm1.353 2.801v-3.999H3.84v4h.79Zm1.493-1.59v1.59h-.791v-3.999H7.88v.653H6.124v1.117h1.605v.638z" />
                                </svg> Séries</h5>
                            <div class="card-body text-center">
                                <table class="table table-borderless" id="userTable">
                                    <thead>
                                        <th>Thème</th>
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $IdRole = 2;
                                        $stmt = $db->prepare("SELECT * FROM serie WHERE 1");
                                        $stmt->execute();
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($row as $serie) {
                                            echo
                                                "<tr>
                                            <td>" . $serie['Theme'] . "</td>
                                            <td>" . $serie['description'] . "</td>
                                        </tr>";

                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row m-1">
                        <div class="card" style="border-radius:10px;">
                            <h5 class="p-2"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                    class="bi bi-card-image" viewBox="0 0 16 16">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                    <path
                                        d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
                                </svg> Cartes</h5>
                            <div class="card-body text-center">
                                <table class="table table-borderless" id="userTable">
                                    <thead>
                                        <th>Intitule</th>
                                        <th>Image</th>
                                        <th>Son</th>
                                        
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $db->prepare("SELECT * FROM carte WHERE 1");
                                        $stmt->execute();
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($row as $carte) {

                                           

                                            echo
                                                "<tr>
                                            <td>" . $carte['Intitule'] . "</td>
                                            <td><img src='image/" . $carte['IdImage'] . ".gif' width='30px'></td>
                                            <td>
                                                <audio controls style='width:150px;height:20px'>
                                                    <source src='son/" . $carte['IdSon'] . ".mp3' type='audio/mpeg'>
                                                    Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                                </audio>
                                          </td>
                                            
                                            <td>" . $carte['description'] . "</td>
                                        </tr>";

                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row m-1">
                        <div class="card" style="border-radius:10px;">
                            <h5 class="p-2"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-ear" viewBox="0 0 16 16">
                                    <path
                                        d="M8.5 1A4.5 4.5 0 0 0 4 5.5v7.047a2.453 2.453 0 0 0 4.75.861l.512-1.363a5.6 5.6 0 0 1 .816-1.46l2.008-2.581A4.34 4.34 0 0 0 8.66 1zM3 5.5A5.5 5.5 0 0 1 8.5 0h.16a5.34 5.34 0 0 1 4.215 8.618l-2.008 2.581a4.6 4.6 0 0 0-.67 1.197l-.51 1.363A3.453 3.453 0 0 1 3 12.547zM8.5 4A1.5 1.5 0 0 0 7 5.5v2.695q.168-.09.332-.192c.327-.208.577-.44.72-.727a.5.5 0 1 1 .895.448c-.256.513-.673.865-1.079 1.123A9 9 0 0 1 7 9.313V11.5a.5.5 0 0 1-1 0v-6a2.5 2.5 0 0 1 5 0V6a.5.5 0 0 1-1 0v-.5A1.5 1.5 0 0 0 8.5 4" />
                                </svg> Sons</h5>
                            <div class="card-body text-center">
                                <table class="table table-borderless" id="userTable">
                                    <thead>
                                        <th>Intitule</th>
                                        <th>Son</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $IdRole = 2;
                                        $stmt = $db->prepare("SELECT * FROM son WHERE 1");
                                        $stmt->execute();
                                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($row as $son) {
                                            echo
                                                "<tr>
                                            <td>" . $son['Intitule'] . "</td>
                                            <td> 
                                                <audio controls style='width:150px;height:20px'>
                                                    <source src='son/" . $son['Id'] . ".mp3' type='audio/mpeg'>
                                                        Votre navigateur ne prend pas en charge l'élément <code>audio</code>.
                                                </audio>
                                            </td>
                                        </tr>";

                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            slotMinTime: '07:00:00',
            slotMaxTime: '19:00:00',
            allDaySlot: false,
        });
        calendar.render();
    });
</script>