<!DOCTYPE html>
<html>
<head><title>Admin - Situation des gains</title></head>
<body>
<h1>Situation des gains (frais collectes)</h1>
<a href="<?= site_url('admin') ?>">&larr; Dashboard</a>

<!-- Filtre par periode -->
<form method="get" action="<?= site_url('admin/stats/gains') ?>">
    <label>Du :</label>
    <input type="date" name="date_debut" value="<?= esc($date_debut ?? '') ?>">
    <label>Au :</label>
    <input type="date" name="date_fin" value="<?= esc($date_fin ?? '') ?>">
    <button type="submit">Filtrer</button>
</form>

<!-- Gains par type d'operation -->
<h2>Gains par type d'operation</h2>
<table border="1">
    <tr>
        <th>Type</th>
        <th>Nombre de transactions</th>
        <th>Total frais</th>
    </tr>
    <?php $total_general = 0; ?>
    <?php foreach ($gains as $g): ?>
    <tr>
        <td><?= esc($g['libelle']) ?></td>
        <td><?= $g['nb_transactions'] ?></td>
        <td><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
    </tr>
    <?php $total_general += $g['total_frais']; ?>
    <?php endforeach; ?>
    <tr>
        <td><strong>TOTAL</strong></td>
        <td></td>
        <td><strong><?= number_format($total_general, 0, ',', ' ') ?> Ar</strong></td>
    </tr>
</table>
</body>
</html>
