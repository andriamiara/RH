<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$isEdit = $editingEmploye !== null;
$formAction = $isEdit ? site_url('/admin/employes/' . $editingEmploye['id']) : site_url('/admin/employes');
$title = $isEdit ? 'Modifier un employe' : 'Ajouter un employe';
$submitLabel = $isEdit ? 'Mettre a jour' : 'Creer l\'employe';

if (! function_exists('adminRoleBadge')) {
    function adminRoleBadge(string $role): string
    {
        return match ($role) {
            'rh'    => 't-maladie',
            'admin' => 't-special',
            default => '',
        };
    }
}
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>" class="active"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Gestion des employes</div><div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employes</div></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <div class="form-section">
                <h3><?= esc($title) ?></h3>
                <form method="post" action="<?= $formAction ?>">
                    <?= csrf_field() ?>
                    <div class="form-grid-2" style="margin-bottom:1rem">
                        <div class="f-group">
                            <label class="f-label">Prenom</label>
                            <input type="text" name="prenom" class="f-input" value="<?= esc(old('prenom', $editingEmploye['prenom'] ?? '')) ?>">
                            <?php if (session('errors.prenom')): ?><div class="f-error"><?= esc(session('errors.prenom')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Nom</label>
                            <input type="text" name="nom" class="f-input" value="<?= esc(old('nom', $editingEmploye['nom'] ?? '')) ?>">
                            <?php if (session('errors.nom')): ?><div class="f-error"><?= esc(session('errors.nom')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Email</label>
                            <input type="email" name="email" class="f-input" value="<?= esc(old('email', $editingEmploye['email'] ?? '')) ?>">
                            <?php if (session('errors.email')): ?><div class="f-error"><?= esc(session('errors.email')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label"><?= $isEdit ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe initial' ?></label>
                            <input type="password" name="password" class="f-input" placeholder="<?= $isEdit ? 'Laisser vide pour conserver' : 'A communiquer a l\'employe' ?>">
                            <?php if (session('errors.password')): ?><div class="f-error"><?= esc(session('errors.password')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Departement</label>
                            <select name="departement_id" class="f-select">
                                <option value="">-- Choisir --</option>
                                <?php foreach ($departements as $departement): ?>
                                    <option value="<?= esc($departement['id']) ?>" <?= (string) old('departement_id', $editingEmploye['departement_id'] ?? '') === (string) $departement['id'] ? 'selected' : '' ?>><?= esc($departement['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.departement_id')): ?><div class="f-error"><?= esc(session('errors.departement_id')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Role</label>
                            <select name="role" class="f-select">
                                <?php foreach (['employe' => 'Employe', 'rh' => 'Responsable RH', 'admin' => 'Administrateur'] as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= old('role', $editingEmploye['role'] ?? 'employe') === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.role')): ?><div class="f-error"><?= esc(session('errors.role')) ?></div><?php endif; ?>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Date d'embauche</label>
                            <input type="date" name="date_embauche" class="f-input" value="<?= esc(old('date_embauche', isset($editingEmploye['date_embauche']) ? substr($editingEmploye['date_embauche'], 0, 10) : date('Y-m-d'))) ?>">
                            <?php if (session('errors.date_embauche')): ?><div class="f-error"><?= esc(session('errors.date_embauche')) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn-forest" type="submit"><i class="bi bi-save"></i> <?= esc($submitLabel) ?></button>
                        <?php if ($isEdit): ?><a href="<?= site_url('/admin/employes') ?>" class="btn-secondary">Annuler l'edition</a><?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Tous les employes</h3></div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Departement</th><th>Role</th><th>Embauche</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($employes as $employe): ?>
                            <tr style="<?= (int) $employe['actif'] === 1 ? '' : 'opacity:.55' ?>">
                                <td class="td-name"><?= esc($employe['prenom'] . ' ' . $employe['nom']) ?><div class="td-muted" style="font-size:.78rem"><?= esc($employe['email']) ?></div></td>
                                <td class="td-muted"><?= esc($employe['departement_nom'] ?? '-') ?></td>
                                <td><span class="type-badge <?= esc(adminRoleBadge($employe['role'])) ?>"><?= esc($employe['role']) ?></span></td>
                                <td class="td-muted td-mono" style="font-size:.78rem"><?= esc(substr((string) $employe['date_embauche'], 0, 10)) ?></td>
                                <td><span class="statut <?= (int) $employe['actif'] === 1 ? 's-approuvee' : 's-annulee' ?>"><?= (int) $employe['actif'] === 1 ? 'actif' : 'inactif' ?></span></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?= site_url('/admin/employes?edit=' . $employe['id']) ?>" class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Editer</a>
                                        <?php if ((int) $employe['actif'] === 1): ?>
                                            <form method="post" action="<?= site_url('/admin/employes/' . $employe['id'] . '/desactiver') ?>" style="display:inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-sm btn-del"><i class="bi bi-slash-circle"></i> Desactiver</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="td-muted">-</span>
                                        <?php endif; ?>
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
