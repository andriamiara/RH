<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $editingType !== null;
$formAction = $isEdit ? site_url('/admin/types-conge/' . $editingType['id']) : site_url('/admin/types-conge');
$title = $isEdit ? 'Modifier un type de conge' : 'Ajouter un type de conge';
$submitLabel = $isEdit ? 'Mettre a jour' : 'Creer le type';
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>" class="active"><i class="bi bi-tags"></i> Types de conge</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Gestion des types de conge</div><div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Types de conge</div></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="form-section">
                <h3><?= esc($title) ?></h3>
                <form method="post" action="<?= $formAction ?>">
                    <?= csrf_field() ?>
                    <div class="form-grid-2" style="margin-bottom:1rem">
                        <div class="f-group">
                            <label class="f-label">Libelle</label>
                            <input type="text" name="libelle" class="f-input" value="<?= esc(old('libelle', $editingType['libelle'] ?? '')) ?>">
                            <?php if (session('errors.libelle')): ?><div class="f-error"><?= esc(session('errors.libelle')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Jours annuels</label>
                            <input type="number" min="0" name="jours_annuels" class="f-input" value="<?= esc(old('jours_annuels', isset($editingType['jours_annuels']) ? (string) $editingType['jours_annuels'] : '0')) ?>">
                            <?php if (session('errors.jours_annuels')): ?><div class="f-error"><?= esc(session('errors.jours_annuels')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Deductible du solde</label>
                            <select name="deductible" class="f-select">
                                <option value="1" <?= old('deductible', isset($editingType['deductible']) ? (string) $editingType['deductible'] : '1') === '1' ? 'selected' : '' ?>>Oui</option>
                                <option value="0" <?= old('deductible', isset($editingType['deductible']) ? (string) $editingType['deductible'] : '1') === '0' ? 'selected' : '' ?>>Non</option>
                            </select>
                            <?php if (session('errors.deductible')): ?><div class="f-error"><?= esc(session('errors.deductible')) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn-forest" type="submit"><i class="bi bi-save"></i> <?= esc($submitLabel) ?></button>
                        <?php if ($isEdit): ?><a href="<?= site_url('/admin/types-conge') ?>" class="btn-secondary">Annuler l'edition</a><?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Tous les types de conge</h3></div>
                <table class="tbl">
                    <thead><tr><th>Libelle</th><th>Jours annuels</th><th>Deductible</th><th>Soldes</th><th>Demandes</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($typesConge as $type): ?>
                            <tr>
                                <td class="td-name"><?= esc($type['libelle']) ?></td>
                                <td class="td-mono"><?= esc((string) $type['jours_annuels']) ?></td>
                                <td><span class="statut <?= (int) $type['deductible'] === 1 ? 's-approuvee' : 's-annulee' ?>"><?= (int) $type['deductible'] === 1 ? 'oui' : 'non' ?></span></td>
                                <td class="td-mono"><?= esc((string) $type['soldes_count']) ?></td>
                                <td class="td-mono"><?= esc((string) $type['conges_count']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= site_url('/admin/types-conge?edit=' . $type['id']) ?>" class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Editer</a>
                                        <form method="post" action="<?= site_url('/admin/types-conge/' . $type['id'] . '/supprimer') ?>" style="display:inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-sm btn-del"><i class="bi bi-trash"></i> Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
