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
</head>
<body>

    <h1>Connexion Mobile Money</h1>

    <!-- Affichage des messages d'erreur (flash session) -->
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire de connexion -->
    <!-- Le formulaire envoie le numero en POST vers /login (AuthController::login) -->
    <form action="<?= base_url('login') ?>" method="post">

        <!-- Champ de saisie du numero de telephone -->
        <label for="numero_telephone">Numero de telephone :</label><br>
        <input
            type="text"
            name="numero_telephone"
            id="numero_telephone"
            placeholder="Ex: 0331234567"
            maxlength="10"
            required
        ><br><br>

        <!-- Bouton de soumission -->
        <button type="submit">Se connecter</button>

    </form>

    <!-- Information pour l'utilisateur -->
    <p><em>Pas besoin d'inscription. Entrez votre numero et votre compte sera cree automatiquement.</em></p>

</body>
</html>
