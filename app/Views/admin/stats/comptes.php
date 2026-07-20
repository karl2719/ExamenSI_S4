<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Comptes clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Situation des comptes clients</h1>
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Dashboard</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Numero telephone</th>
                        <th>Operateur</th>
                        <th>Solde</th>
                        <th>Date creation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $c): ?>
                    <tr>
                        <td><?= $c['id_client'] ?></td>
                        <td><?= esc($c['numero_telephone']) ?></td>
                        <td><?= esc($c['nom_operateur']) ?></td>
                        <td><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
                        <td><?= $c['date_creation'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
