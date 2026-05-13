<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$currentUser = session()->get('user');
$userPrenom = is_array($currentUser) ? (string) ($currentUser['prenom'] ?? '') : '';
$userNom = is_array($currentUser) ? (string) ($currentUser['nom'] ?? '') : '';
$userRole = is_array($currentUser) ? (string) ($currentUser['role'] ?? '') : '';
$initials = '';
if ($userPrenom !== '' || $userNom !== '') {
    $initials = strtoupper(substr($userPrenom, 0, 1) . substr($userNom, 0, 1));
}
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-people"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace RH</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Demandes</a></li>
            <li><a href="<?= site_url('/rh/soldes') ?>" class="active"><i class="bi bi-clipboard-data"></i> Soldes employes</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-blue"><?= esc($initials !== '' ? $initials : 'RH') ?></div><div><div class="user-name"><?= esc(trim($userPrenom . ' ' . $userNom)) ?></div><div class="user-role"><?= esc($userRole !== '' ? $userRole : 'RH') ?></div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-blue"><?= esc($initials !== '' ? $initials : 'RH') ?></div><div><div class="user-name"><?= esc(trim($userPrenom . ' ' . $userNom)) ?></div><div class="user-role"><?= esc($userRole !== '' ? $userRole : 'RH') ?></div></div><?= view('partials/logout_form') ?></div></div>
        <div class="topbar">
            <div>
                <div class="topbar-title">Soldes des employes</div>
                <div class="topbar-breadcrumb"><a href="<?= site_url('/rh/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Soldes</div>
            </div>
        </div>
        <div class="content">
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Soldes par employe</h3>
                    <form method="get" action="<?= site_url('/rh/soldes') ?>" style="display:flex;gap:8px;flex-wrap:wrap">
                        <select name="departement" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
                            <option value="">Tous les departements</option>
                            <?php foreach (($departements ?? []) as $dept): ?>
                                <option value="<?= esc($dept['id']) ?>" <?= ($current_departement ?? '') == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input name="annee" class="f-input" style="width:110px;padding:6px 10px;font-size:.8rem" placeholder="Annee" value="<?= esc($current_annee ?? '') ?>" />
                        <button class="btn-secondary" type="submit" style="padding:6px 12px;font-size:.8rem">Filtrer</button>
                    </form>
                </div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Departement</th><th>Type</th><th>Annee</th><th>Attribues</th><th>Pris</th><th>Restant</th></tr></thead>
                    <tbody>
                        <?php if (! empty($soldes)): ?>
                            <?php foreach ($soldes as $solde): ?>
                                <?php $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris']; ?>
                                <tr>
                                    <td class="td-name"><?= esc($solde['prenom'] . ' ' . $solde['nom']) ?></td>
                                    <td class="td-muted"><?= esc($solde['dept_nom'] ?? '-') ?></td>
                                    <td><span class="type-badge t-annuel"><?= esc($solde['libelle']) ?></span></td>
                                    <td class="td-mono"><?= esc($solde['annee']) ?></td>
                                    <td class="td-mono"><?= esc($solde['jours_attribues']) ?> j</td>
                                    <td class="td-mono"><?= esc($solde['jours_pris']) ?> j</td>
                                    <td>
                                        <span style="font-family:'DM Mono',monospace;color:<?= $restant <= 0 ? 'var(--danger)' : ($restant <= 2 ? 'var(--warn)' : 'var(--success)') ?>">
                                            <?= esc($restant) ?> j
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty"><i class="bi bi-clipboard-data"></i><p>Aucun solde pour ce filtre.</p></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
