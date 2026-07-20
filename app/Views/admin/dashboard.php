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
            <span class="navbar-brand">Backoffice Admin</span>
            <a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-light btn-sm">Deconnexion</a>
        </div>
    </nav>

    <div class="container">

        <h1 class="h3 mb-3">Backoffice - Tableau de bord</h1>
        <p>Bienvenue, <strong><?= session()->get('admin_nom') ?></strong></p>

        <!-- Menu principal -->
        <div class="list-group mb-4">
            <a href="<?= site_url('admin/operateurs') ?>" class="list-group-item list-group-item-action">Gestion des operateurs</a>
            <a href="<?= site_url('admin/baremes') ?>" class="list-group-item list-group-item-action">Gestion des baremes de frais</a>
            <a href="<?= site_url('admin/stats/gains') ?>" class="list-group-item list-group-item-action">Situation des gains</a>
            <a href="<?= site_url('admin/stats/comptes') ?>" class="list-group-item list-group-item-action">Situation des comptes clients</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
