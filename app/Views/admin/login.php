<!DOCTYPE html>
<html>
<head><title>Admin - Login</title></head>
<body>
<h1>Backoffice - Connexion</h1>

<!-- Message d'erreur si login echoue -->
<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/login') ?>">
    <p>
        <label>Nom utilisateur :</label><br>
        <input type="text" name="nom_utilisateur" required>
    </p>
    <p>
        <label>Mot de passe :</label><br>
        <input type="password" name="mot_de_passe" required>
    </p>
    <button type="submit">Se connecter</button>
</form>
</body>
</html>
