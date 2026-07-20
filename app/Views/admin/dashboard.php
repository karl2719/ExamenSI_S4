<!DOCTYPE html>
<html>
<head><title>Admin - Dashboard</title></head>
<body>
<h1>Backoffice - Tableau de bord</h1>
<p>Bienvenue, <?= session()->get('admin_nom') ?></p>

<!-- Menu principal -->
<ul>
    <li><a href="<?= site_url('admin/operateurs') ?>">Gestion des operateurs</a></li>
    <li><a href="<?= site_url('admin/baremes') ?>">Gestion des baremes de frais</a></li>
    <li><a href="<?= site_url('admin/stats/gains') ?>">Situation des gains</a></li>
    <li><a href="<?= site_url('admin/stats/comptes') ?>">Situation des comptes clients</a></li>
</ul>

<a href="<?= site_url('admin/logout') ?>">Deconnexion</a>
</body>
</html>
