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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <span class="navbar-brand">Mobile Money</span>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Se deconnecter</a>
        </div>
    </nav>

    <div class="container">

        <!-- En-tete avec le numero du client -->
        <h1 class="h3">Bienvenue, <?= esc($client['numero_telephone']) ?></h1>

        <!-- Messages flash de succes (apres une operation reussie) -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <strong>Succes :</strong> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Messages flash d'erreur -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Affichage du solde actuel du client -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h2 class="card-title h4">Votre solde</h2>
                <p class="display-6 fw-bold text-primary"><?= number_format($solde, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>

        <!-- Boutons des operations disponibles -->
        <h3 class="h5 mb-3">Operations</h3>
        <div class="list-group mb-4">
            <!-- Depot : ajouter de l'argent sur le compte (automatique, sans frais) -->
            <a href="<?= base_url('transaction/depot') ?>" class="list-group-item list-group-item-action">Faire un depot</a>

            <!-- Retrait : retirer de l'argent (automatique, avec frais selon bareme) -->
            <a href="<?= base_url('transaction/retrait') ?>" class="list-group-item list-group-item-action">Faire un retrait</a>

            <!-- Transfert : envoyer de l'argent a un autre numero -->
            <a href="<?= base_url('transaction/transfert') ?>" class="list-group-item list-group-item-action">Faire un transfert</a>

            <!-- Historique : voir toutes les transactions passees -->
            <a href="<?= base_url('transaction/historique') ?>" class="list-group-item list-group-item-action">Voir l'historique</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
