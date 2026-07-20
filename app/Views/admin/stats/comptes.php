<!DOCTYPE html>
<html>
<head><title>Admin - Comptes clients</title></head>
<body>
<h1>Situation des comptes clients</h1>
<a href="<?= site_url('admin') ?>">&larr; Dashboard</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Numero telephone</th>
        <th>Operateur</th>
        <th>Solde</th>
        <th>Date creation</th>
    </tr>
    <?php foreach ($comptes as $c): ?>
    <tr>
        <td><?= $c['id_client'] ?></td>
        <td><?= esc($c['numero_telephone']) ?></td>
        <td><?= esc($c['nom_operateur']) ?></td>
        <td><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
        <td><?= $c['date_creation'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
