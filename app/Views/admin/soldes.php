<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $editingSolde !== null;
$title = $isEdit ? 'Ajuster un solde annuel' : 'Initialiser un solde annuel';
$submitLabel = $isEdit ? 'Mettre a jour le solde' : 'Initialiser le solde';
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
            <li><a href="<?= site_url('/admin/soldes') ?>" class="active"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Gestion des soldes annuels</div><div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes annuels</div></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="form-section">
                <h3><?= esc($title) ?></h3>
                <form method="post" action="<?= site_url('/admin/soldes') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="solde_id" value="<?= esc((string) ($editingSolde['id'] ?? '')) ?>">
                    <div class="form-grid-2" style="margin-bottom:1rem">
                        <div class="f-group">
                            <label class="f-label">Employe</label>
                            <select name="employe_id" class="f-select">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($employes as $employe): ?>
                                    <option value="<?= esc($employe['id']) ?>" <?= (string) old('employe_id', $editingSolde['employe_id'] ?? '') === (string) $employe['id'] ? 'selected' : '' ?>>
                                        <?= esc($employe['prenom'] . ' ' . $employe['nom'] . ' - ' . $employe['email']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.employe_id')): ?><div class="f-error"><?= esc(session('errors.employe_id')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Type de conge</label>
                            <select name="type_conge_id" class="f-select">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($typesConge as $type): ?>
                                    <option value="<?= esc($type['id']) ?>" <?= (string) old('type_conge_id', $editingSolde['type_conge_id'] ?? '') === (string) $type['id'] ? 'selected' : '' ?>>
                                        <?= esc($type['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.type_conge_id')): ?><div class="f-error"><?= esc(session('errors.type_conge_id')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Annee</label>
                            <input type="number" min="2000" name="annee" class="f-input" value="<?= esc(old('annee', isset($editingSolde['annee']) ? (string) $editingSolde['annee'] : date('Y'))) ?>">
                            <?php if (session('errors.annee')): ?><div class="f-error"><?= esc(session('errors.annee')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Jours attribues</label>
                            <input type="number" min="0" name="jours_attribues" class="f-input" value="<?= esc(old('jours_attribues', isset($editingSolde['jours_attribues']) ? (string) $editingSolde['jours_attribues'] : '0')) ?>">
                            <?php if (session('errors.jours_attribues')): ?><div class="f-error"><?= esc(session('errors.jours_attribues')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Jours pris</label>
                            <input type="number" min="0" name="jours_pris" class="f-input" value="<?= esc(old('jours_pris', isset($editingSolde['jours_pris']) ? (string) $editingSolde['jours_pris'] : '0')) ?>">
                            <?php if (session('errors.jours_pris')): ?><div class="f-error"><?= esc(session('errors.jours_pris')) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn-forest" type="submit"><i class="bi bi-save"></i> <?= esc($submitLabel) ?></button>
                        <?php if ($isEdit): ?><a href="<?= site_url('/admin/soldes') ?>" class="btn-secondary">Annuler l'edition</a><?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Soldes existants</h3></div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Type</th><th>Annee</th><th>Attribues</th><th>Pris</th><th>Restant calcule</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($soldes as $solde): ?>
                            <tr>
                                <td class="td-name"><?= esc($solde['prenom'] . ' ' . $solde['nom']) ?><div class="td-muted" style="font-size:.78rem"><?= esc($solde['email']) ?></div></td>
                                <td><?= esc($solde['type_libelle']) ?></td>
                                <td class="td-mono"><?= esc((string) $solde['annee']) ?></td>
                                <td class="td-mono"><?= esc((string) $solde['jours_attribues']) ?></td>
                                <td class="td-mono"><?= esc((string) $solde['jours_pris']) ?></td>
                                <td class="td-mono"><?= esc((string) ((int) $solde['jours_attribues'] - (int) $solde['jours_pris'])) ?></td>
                                <td><a href="<?= site_url('/admin/soldes?edit=' . $solde['id']) ?>" class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Ajuster</a></td>
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
