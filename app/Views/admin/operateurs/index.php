<!DOCTYPE html>
<html>
<head><title>Admin - Operateurs</title></head>
<body>
<h1>Liste des operateurs</h1>
<a href="<?= site_url('admin') ?>">&larr; Dashboard</a> |
<a href="<?= site_url('admin/operateurs/create') ?>">+ Ajouter un operateur</a>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Code</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($operateurs as $op): ?>
    <tr>
        <td><?= $op['id_operateur'] ?></td>
        <td><?= esc($op['nom']) ?></td>
        <td><?= esc($op['code']) ?></td>
        <td>
            <a href="<?= site_url('admin/operateurs/edit/' . $op['id_operateur']) ?>">Modifier</a> |
            <a href="<?= site_url('admin/operateurs/' . $op['id_operateur'] . '/prefixes') ?>">Prefixes</a> |
            <a href="<?= site_url('admin/operateurs/delete/' . $op['id_operateur']) ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
