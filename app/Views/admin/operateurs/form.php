<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> Operateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3"><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> un operateur</h1>
        <a href="<?= site_url('admin/operateurs') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Retour</a>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Formulaire creation/edition operateur -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= isset($operateur) ? site_url('admin/operateurs/update/' . $operateur['id_operateur']) : site_url('admin/operateurs/store') ?>">
                    <div class="mb-3">
                        <label class="form-label">Nom :</label>
                        <input type="text" name="nom" class="form-control" value="<?= esc($operateur['nom'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Code :</label>
                        <input type="text" name="code" class="form-control" value="<?= esc($operateur['code'] ?? '') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?></button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
