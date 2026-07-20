<!DOCTYPE html>
<html>
<head><title>Admin - Prefixes de <?= esc($operateur['nom']) ?></title></head>
<body>
<h1>Prefixes de l'operateur : <?= esc($operateur['nom']) ?> (<?= esc($operateur['code']) ?>)</h1>
<a href="<?= site_url('admin/operateurs') ?>">&larr; Retour aux operateurs</a>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<!-- Liste des prefixes -->
<table border="1">
    <tr><th>Prefixe</th><th>Action</th></tr>
    <?php foreach ($prefixes as $p): ?>
    <tr>
        <td><?= esc($p['prefixe']) ?></td>
        <td><a href="<?= site_url('admin/prefixes/delete/' . $p['id_prefixe']) ?>" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Formulaire ajout prefixe -->
<h3>Ajouter un prefixe</h3>
<form method="post" action="<?= site_url('admin/operateurs/' . $operateur['id_operateur'] . '/prefixes/store') ?>">
    <input type="text" name="prefixe" placeholder="Ex: 033" required>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>
