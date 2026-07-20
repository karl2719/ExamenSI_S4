<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <!-- Nom de l'application dans la barre de navigation -->
            <span class="navbar-brand">Backoffice Admin</span>
            <!-- Bouton de deconnexion -->
            <a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">Deconnexion</a>
        </div>
    </nav>

    <div class="container">

        <!-- Titre du tableau de bord -->
        <h1 class="h3 mb-3">Backoffice - Tableau de bord</h1>
        <!-- Affiche le nom de l'admin connecte (recupere depuis la session) -->
        <p>Bienvenue, <strong><?= session()->get('admin_nom') ?></strong></p>
        <!-- V2: Affiche l'operateur lie a cet admin -->
        <p>Operateur : <span class="badge bg-primary"><?= esc($nom_operateur ?? 'Non defini') ?></span></p>

        <!-- Menu principal du backoffice -->
        <div class="list-group mb-4">
            <!-- Lien vers la gestion des operateurs -->
            <a href="<?= site_url('admin/operateurs') ?>" class="list-group-item list-group-item-action">Gestion des operateurs</a>
            <!-- Lien vers la gestion des baremes de frais -->
            <a href="<?= site_url('admin/baremes') ?>" class="list-group-item list-group-item-action">Gestion des baremes de frais</a>
            <!-- V2: Lien vers la gestion des commissions inter-operateur -->
            <a href="<?= site_url('admin/commissions') ?>" class="list-group-item list-group-item-action list-group-item-info">Commissions inter-operateur</a>
            <!-- Lien vers la page des gains (frais collectes) -->
            <a href="<?= site_url('admin/stats/gains') ?>" class="list-group-item list-group-item-action">Situation des gains</a>
            <!-- V2: Lien vers les montants a envoyer par operateur -->
            <a href="<?= site_url('admin/stats/montants-operateurs') ?>" class="list-group-item list-group-item-action list-group-item-warning">Montants a envoyer par operateur</a>
            <!-- Lien vers la situation des comptes clients -->
            <a href="<?= site_url('admin/stats/comptes') ?>" class="list-group-item list-group-item-action">Situation des comptes clients</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
