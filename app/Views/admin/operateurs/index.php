<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Operateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Liste des operateurs</h1>
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm">&larr; Dashboard</a>
        <a href="<?= site_url('admin/operateurs/create') ?>" class="btn btn-primary btn-sm">+ Ajouter un operateur</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operateurs as $op): ?>
                    <tr>
                        <td><?= $op['id_operateur'] ?></td>
                        <td><?= esc($op['nom']) ?></td>
                        <td><?= esc($op['code']) ?></td>
                        <td>
                            <a href="<?= site_url('admin/operateurs/edit/' . $op['id_operateur']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="<?= site_url('admin/operateurs/' . $op['id_operateur'] . '/prefixes') ?>" class="btn btn-info btn-sm">Prefixes</a>
                            <a href="<?= site_url('admin/operateurs/delete/' . $op['id_operateur']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
