<!DOCTYPE html>
<html>
<head><title>Admin - <?= isset($bareme) ? 'Modifier' : 'Ajouter' ?> Bareme</title></head>
<body>
<h1><?= isset($bareme) ? 'Modifier' : 'Ajouter' ?> un bareme de frais</h1>
<a href="<?= site_url('admin/baremes') ?>">&larr; Retour</a>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<!-- Formulaire creation/edition bareme -->
<form method="post" action="<?= isset($bareme) ? site_url('admin/baremes/update/' . $bareme['id_bareme']) : site_url('admin/baremes/store') ?>">
    <p>
        <label>Type d'operation :</label><br>
        <select name="id_type" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($types as $type): ?>
            <option value="<?= $type['id_type'] ?>" <?= (isset($bareme) && $bareme['id_type'] == $type['id_type']) ? 'selected' : '' ?>>
                <?= esc($type['libelle']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Montant minimum (Ar) :</label><br>
        <input type="number" name="montant_min" value="<?= esc($bareme['montant_min'] ?? '') ?>" required>
    </p>
    <p>
        <label>Montant maximum (Ar) :</label><br>
        <input type="number" name="montant_max" value="<?= esc($bareme['montant_max'] ?? '') ?>" required>
    </p>
    <p>
        <label>Frais (Ar) :</label><br>
        <input type="number" name="frais" value="<?= esc($bareme['frais'] ?? '') ?>" required>
    </p>
    <button type="submit"><?= isset($bareme) ? 'Modifier' : 'Ajouter' ?></button>
</form>
</body>
</html>
