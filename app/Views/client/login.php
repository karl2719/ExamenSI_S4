<!-- ==========================================================
     Vue : Ecran de connexion (Login)
     
     Cette page permet au client de se connecter en saisissant
     son numero de telephone. Pas d'inscription prealable :
     le compte est cree automatiquement si le numero est nouveau.
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">

                        <h1 class="card-title text-center mb-4 h3">Connexion Mobile Money</h1>

                        <!-- Affichage des messages d'erreur (flash session) -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire de connexion -->
                        <!-- Le formulaire envoie le numero en POST vers /login (AuthController::login) -->
                        <form action="<?= base_url('login') ?>" method="post">

                            <!-- Champ de saisie du numero de telephone -->
                            <div class="mb-3">
                                <label for="numero_telephone" class="form-label">Numero de telephone :</label>
                                <input
                                    type="text"
                                    name="numero_telephone"
                                    id="numero_telephone"
                                    class="form-control"
                                    placeholder="Ex: 0331234567"
                                    maxlength="10"
                                    required
                                >
                            </div>

                            <!-- Bouton de soumission -->
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>

                        </form>

                        <!-- Information pour l'utilisateur -->
                        <p class="text-muted text-center mt-3 mb-0"><em>Pas besoin d'inscription. Entrez votre numero et votre compte sera cree automatiquement.</em></p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
