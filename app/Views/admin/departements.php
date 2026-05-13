<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $editingDepartement !== null;
$formAction = $isEdit ? site_url('/admin/departements/' . $editingDepartement['id']) : site_url('/admin/departements');
$title = $isEdit ? 'Modifier un departement' : 'Ajouter un departement';
$submitLabel = $isEdit ? 'Mettre a jour' : 'Creer le departement';
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>" class="active"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Gestion des departements</div><div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Departements</div></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="form-section">
                <h3><?= esc($title) ?></h3>
                <form method="post" action="<?= $formAction ?>">
                    <?= csrf_field() ?>
                    <div class="f-group">
                        <label class="f-label">Nom</label>
                        <input type="text" name="nom" class="f-input" value="<?= esc(old('nom', $editingDepartement['nom'] ?? '')) ?>">
                        <?php if (session('errors.nom')): ?><div class="f-error"><?= esc(session('errors.nom')) ?></div><?php endif; ?>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Description</label>
                        <textarea name="description" class="f-textarea" placeholder="Description courte du departement"><?= esc(old('description', $editingDepartement['description'] ?? '')) ?></textarea>
                        <?php if (session('errors.description')): ?><div class="f-error"><?= esc(session('errors.description')) ?></div><?php endif; ?>
                    </div>
                    <div class="form-actions">
                        <button class="btn-forest" type="submit"><i class="bi bi-save"></i> <?= esc($submitLabel) ?></button>
                        <?php if ($isEdit): ?><a href="<?= site_url('/admin/departements') ?>" class="btn-secondary">Annuler l'edition</a><?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Tous les departements</h3></div>
                <table class="tbl">
                    <thead><tr><th>Nom</th><th>Description</th><th>Employes</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($departements as $departement): ?>
                            <tr>
                                <td class="td-name"><?= esc($departement['nom']) ?></td>
                                <td class="td-muted"><?= esc($departement['description'] ?: '-') ?></td>
                                <td class="td-mono"><?= esc((string) $departement['employes_count']) ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= site_url('/admin/departements?edit=' . $departement['id']) ?>" class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Editer</a>
                                        <form method="post" action="<?= site_url('/admin/departements/' . $departement['id'] . '/supprimer') ?>" style="display:inline">
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
