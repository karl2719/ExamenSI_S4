<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= isset($bareme) ? 'Modifier' : 'Ajouter' ?> Bareme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3"><?= isset($bareme) ? 'Modifier' : 'Ajouter' ?> un bareme de frais</h1>
        <a href="<?= site_url('admin/baremes') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Retour</a>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Formulaire creation/edition bareme -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?= isset($bareme) ? site_url('admin/baremes/update/' . $bareme['id_bareme']) : site_url('admin/baremes/store') ?>">
                    <div class="mb-3">
                        <label class="form-label">Type d'operation :</label>
                        <select name="id_type" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id_type'] ?>" <?= (isset($bareme) && $bareme['id_type'] == $type['id_type']) ? 'selected' : '' ?>>
                                <?= esc($type['libelle']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant minimum (Ar) :</label>
                        <input type="number" name="montant_min" class="form-control" value="<?= esc($bareme['montant_min'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant maximum (Ar) :</label>
                        <input type="number" name="montant_max" class="form-control" value="<?= esc($bareme['montant_max'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frais (Ar) :</label>
                        <input type="number" name="frais" class="form-control" value="<?= esc($bareme['frais'] ?? '') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= isset($bareme) ? 'Modifier' : 'Ajouter' ?></button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
