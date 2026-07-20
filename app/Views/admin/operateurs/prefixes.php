<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Prefixes de <?= esc($operateur['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Prefixes de l'operateur : <?= esc($operateur['nom']) ?> (<?= esc($operateur['code']) ?>)</h1>
        <a href="<?= site_url('admin/operateurs') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Retour aux operateurs</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Liste des prefixes -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr><th>Prefixe</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><?= esc($p['prefixe']) ?></td>
                        <td><a href="<?= site_url('admin/prefixes/delete/' . $p['id_prefixe']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulaire ajout prefixe -->
        <h3 class="h5 mt-4">Ajouter un prefixe</h3>
        <form method="post" action="<?= site_url('admin/operateurs/' . $operateur['id_operateur'] . '/prefixes/store') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <input type="text" name="prefixe" class="form-control" placeholder="Ex: 033" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
