<!-- ==========================================================
     Vue : Dashboard client
     
     Cette page affiche :
     - Le numero de telephone du client connecte
     - Son solde actuel (calcule depuis les transactions)
     - Les boutons pour acceder aux operations (depot, retrait,
       transfert, historique)
     - Un lien de deconnexion
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mobile Money</title>
</head>
<body>

    <!-- En-tete avec le numero du client -->
    <h1>Bienvenue, <?= esc($client['numero_telephone']) ?></h1>

    <!-- Messages flash de succes (apres une operation reussie) -->
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;">
            <strong>Succes :</strong> <?= session()->getFlashdata('success') ?>
        </p>
    <?php endif; ?>

    <!-- Messages flash d'erreur -->
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <!-- Affichage du solde actuel du client -->
    <h2>Votre solde : <?= number_format($solde, 0, ',', ' ') ?> Ar</h2>

    <!-- Boutons des operations disponibles -->
    <h3>Operations</h3>
    <ul>
        <!-- Depot : ajouter de l'argent sur le compte (automatique, sans frais) -->
        <li><a href="<?= base_url('transaction/depot') ?>">Faire un depot</a></li>

        <!-- Retrait : retirer de l'argent (automatique, avec frais selon bareme) -->
        <li><a href="<?= base_url('transaction/retrait') ?>">Faire un retrait</a></li>

        <!-- Transfert : envoyer de l'argent a un autre numero -->
        <li><a href="<?= base_url('transaction/transfert') ?>">Faire un transfert</a></li>

        <!-- Historique : voir toutes les transactions passees -->
        <li><a href="<?= base_url('transaction/historique') ?>">Voir l'historique</a></li>
    </ul>

    <hr>

    <!-- Lien de deconnexion -->
    <a href="<?= base_url('logout') ?>">Se deconnecter</a>

</body>
</html>
