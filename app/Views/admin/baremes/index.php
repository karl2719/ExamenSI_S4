<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Baremes de frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Baremes de frais</h1>
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm">&larr; Dashboard</a>
        <a href="<?= site_url('admin/baremes/create') ?>" class="btn btn-primary btn-sm">+ Ajouter un bareme</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- Affichage par type d'operation -->
        <?php foreach ($types as $type): ?>
            <h2 class="h5 mt-4"><?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Montant min</th>
                            <th>Montant max</th>
                            <th>Frais</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <a href="<?= site_url('admin/baremes/edit/' . $b['id_bareme']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="<?= site_url('admin/baremes/delete/' . $b['id_bareme']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($baremes_type)): ?>
                        <tr><td colspan="4" class="text-muted">Aucun bareme defini</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
