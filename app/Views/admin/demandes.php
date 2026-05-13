<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
if (! function_exists('adminHistoryTypeClass')) {
    function adminHistoryTypeClass(string $libelle): string
    {
        $value = strtolower($libelle);

        return match (true) {
            str_contains($value, 'maladie') => 't-maladie',
            str_contains($value, 'special') => 't-special',
            str_contains($value, 'sans')    => 't-sans-solde',
            default                         => 't-annuel',
        };
    }
}

if (! function_exists('adminHistoryStatutClass')) {
    function adminHistoryStatutClass(string $statut): string
    {
        return match ($statut) {
            'approuvee' => 's-approuvee',
            'refusee'   => 's-refusee',
            'annulee'   => 's-annulee',
            default     => 's-attente',
        };
    }
}
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/admin/demandes') ?>" class="active"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
            <li><a href="<?= site_url('/admin/soldes') ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Historique complet des demandes</div>
                <div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
            </div>
        </div>
        <div class="content">
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Toutes les demandes</h3>
                    <form method="get" action="<?= site_url('/admin/demandes') ?>" style="display:flex;gap:8px;flex-wrap:wrap">
                        <select name="departement" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
                            <option value="0">Tous les departements</option>
                            <?php foreach ($departements as $departement): ?>
                                <option value="<?= esc($departement['id']) ?>" <?= (string) $currentDepartement === (string) $departement['id'] ? 'selected' : '' ?>><?= esc($departement['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="statut" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
                            <option value="toutes" <?= $currentStatut === 'toutes' ? 'selected' : '' ?>>Tous les statuts</option>
                            <option value="en_attente" <?= $currentStatut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="approuvee" <?= $currentStatut === 'approuvee' ? 'selected' : '' ?>>Approuvee</option>
                            <option value="refusee" <?= $currentStatut === 'refusee' ? 'selected' : '' ?>>Refusee</option>
                            <option value="annulee" <?= $currentStatut === 'annulee' ? 'selected' : '' ?>>Annulee</option>
                        </select>
                        <button class="btn-secondary" type="submit" style="padding:6px 12px;font-size:.8rem">Filtrer</button>
                    </form>
                </div>
                <?php if ($demandes === []): ?>
                    <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande pour ce filtre.</p></div>
                <?php else: ?>
                    <table class="tbl">
                        <thead><tr><th>Employe</th><th>Departement</th><th>Type</th><th>Periode</th><th>Duree</th><th>Statut</th><th>Commentaire RH</th></tr></thead>
                        <tbody>
                            <?php foreach ($demandes as $demande): ?>
                                <tr>
                                    <td class="td-name"><?= esc($demande['prenom'] . ' ' . $demande['nom']) ?></td>
                                    <td class="td-muted"><?= esc($demande['departement_nom'] ?? '-') ?></td>
                                    <td><span class="type-badge <?= esc(adminHistoryTypeClass($demande['type_libelle'])) ?>"><?= esc($demande['type_libelle']) ?></span></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($demande['date_debut']))) ?> - <?= esc(date('d/m/Y', strtotime($demande['date_fin']))) ?></td>
                                    <td class="td-mono"><?= esc((string) $demande['nb_jours']) ?> j</td>
                                    <td><span class="statut <?= esc(adminHistoryStatutClass($demande['statut'])) ?>"><?= esc($demande['statut']) ?></span></td>
                                    <td class="td-muted"><?= esc($demande['commentaire_rh'] ?: '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
