<div class="col-md-2 sidebar">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-primary m-3"
        style="height: 95vh; border-radius: 10px;">
        <a href="/PFE/carteAnimees/accueil.php"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Accueil</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Utilisateur</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/create/user.php">Créer</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/user.php">Afficher</a></li>
                    <!--<li><a class="dropdown-item" href="/PFE/carteAnimees/modifier/choix_user.php">Modifier</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/supprimer/choix_user.php">Supprimer</a></li> -->
                </ul>
            </div>
            <hr>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Carte</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/create/carte.php">Créer</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/carte.php">Afficher</a></li>
                    <!--<li><a class="dropdown-item" href="/PFE/carteAnimees/modifier/choix_carte.php">Modifier</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/supprimer/choix_carte.php">Supprimer</a></li>-->
                </ul>
            </div>
            <hr>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Série</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/create/serie.php">Créer</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/serie.php">Afficher</a></li>
                    <!--<li><a class="dropdown-item" href="/PFE/carteAnimees/modifier/choix_serie.php">Modifier</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/supprimer/choix_serie.php">Supprimer</a></li>-->
                </ul>
            </div>
            <hr>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Son</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/create/son.php">Créer</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/son.php">Afficher</a></li>
                    <!--<li><a class="dropdown-item" href="/PFE/carteAnimees/modifier/choix_son.php">Modifier</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/suprimer/choix_son.php">Supprimer</a></li>-->
                </ul>
            </div>
            <hr>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Image</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/create/image.php">Créer</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/image.php">Afficher</a></li>
                   <!-- <li><a class="dropdown-item" href="/PFE/carteAnimees/modifier/choix_image.php">Modifier</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/supprimer/choix_image.php">Supprimer</a></li>-->
                </ul>
            </div>
            <hr>
            <div class="btn-group">
                <a href="/PFE/carteAnimees/create/attribution.php"><button type="button" class="btn btn-primary">Attribuer une série à un
                        patient</button></a>
            </div>
            <hr>
            <div class="btn-group" <?php if ($_SESSION['IdRole'] !='3')  echo 'style="display:none;"'; ?>>
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Demande</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/creation.php">Création</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/suppression.php">Suppression</a></li>
                </ul>
            </div>
            <div class="btn-group" <?php if ($_SESSION['IdRole'] !='1')  echo 'style="display:none;"'; ?>>
                <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">Mes demande</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/creation.php">Création</a></li>
                    <li><a class="dropdown-item" href="/PFE/carteAnimees/view/suppression.php">Suppression</a></li>
                </ul>
            </div>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="/PFE/carteAnimees/controller/deconnexion.php"><button type="button"
                class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                <path fill-rule="evenodd"
                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
            </svg> Déconnexion</button></a>
        </div>
    </div>
</div>