<!DOCTYPE html>
<html>
<head><title>Admin - Baremes de frais</title></head>
<body>
<h1>Baremes de frais</h1>
<a href="<?= site_url('admin') ?>">&larr; Dashboard</a> |
<a href="<?= site_url('admin/baremes/create') ?>">+ Ajouter un bareme</a>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<!-- Affichage par type d'operation -->
<?php foreach ($types as $type): ?>
    <h2><?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)</h2>
    <table border="1">
        <tr>
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
        <?php
        // Filtre les baremes pour ce type
        $baremes_type = array_filter($baremes, function($b) use ($type) {
            return $b['id_type'] == $type['id_type'];
        });
        ?>
        <?php foreach ($baremes_type as $b): ?>
        <tr>
            <td><?= number_format($b['montant_min'], 0, ',', ' ') ?> Ar</td>
            <td><?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</td>
            <td><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
            <td>
                <a href="<?= site_url('admin/baremes/edit/' . $b['id_bareme']) ?>">Modifier</a> |
                <a href="<?= site_url('admin/baremes/delete/' . $b['id_bareme']) ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($baremes_type)): ?>
        <tr><td colspan="4">Aucun bareme defini</td></tr>
        <?php endif; ?>
    </table>
<?php endforeach; ?>
</body>
</html>
