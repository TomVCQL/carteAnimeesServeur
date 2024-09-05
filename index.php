<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</head>
<style>
    body {
        background-repeat: no-repeat;
        background-size: cover;
        background-image: url("background.jpg");
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <h1 class="navbar-brand">DashBoard</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="card text-center mb-3" style="width: 40rem; margin-right:auto;margin-left:auto;margin-top:10%">
        <h2 style="color:#0d6efd">Connexion</h2>
        <div class="card-body">
            <?php
            if (isset($_GET['erreur']) && $_GET['erreur'] == 1) {
                echo '<div class="alert alert-danger">Mot de passe incorrect</div>';
            }
            elseif (isset($_GET['erreur']) && $_GET['erreur'] == 2)
            {
                echo '<div class="alert alert-danger">email incorrect</div>';
            }
            elseif (isset($_GET['erreur']) && $_GET['erreur'] == 3)
            {
                echo '<div class="alert alert-danger">Vous avez essayer de rejoindre une page sans vous connecter, <br>Veuillez-vous connecter</div>';
            }
            elseif (isset($_GET['erreur']) && $_GET['erreur'] == 4)
            {
                echo '<div class="alert alert-danger">Vous êtes pas autorisés à vous connecter sur ce site</div>';
            }
            ?>
            <form method="post" action="controller/connexion.php">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="email">
                    <label for="email">Adresse Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="mot de passe">
                    <label for="password">Mot de passe</label>
                </div>

                <input type="submit" value="connexion" class="btn btn-primary">
            </form>
        </div>
    </div>
</body>

</html>