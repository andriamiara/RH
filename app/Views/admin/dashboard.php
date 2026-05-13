<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
if (! function_exists('adminDashboardTypeClass')) {
    function adminDashboardTypeClass(string $libelle): string
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

if (! function_exists('adminDashboardStatutClass')) {
    function adminDashboardStatutClass(string $statut): string
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
            <li><a href="<?= site_url('/admin/dashboard') ?>" class="active"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/admin/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
            <li><a href="<?= site_url('/admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de conge</a></li>
            <li><a href="<?= site_url('/admin/soldes') ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Vue d'ensemble</div><div class="topbar-breadcrumb">Administration</div></div><div class="topbar-actions"><a href="<?= site_url('/admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employe</a></div></div>
        <div class="content">
            <div class="metrics">
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div><div class="metric-val"><?= esc((string) $employesActifs) ?></div><div class="metric-label">Employes actifs</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val"><?= esc((string) $demandesEnAttente) ?></div><div class="metric-label">Demandes en attente</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div><div class="metric-val"><?= esc((string) $departementsCount) ?></div><div class="metric-label">Departements</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-tags"></i></div></div><div class="metric-val"><?= esc((string) $typesCongeCount) ?></div><div class="metric-label">Types de conge</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-person-slash"></i></div></div><div class="metric-val"><?= esc((string) $absencesThisMonth) ?></div><div class="metric-label">Absences du mois</div><div class="metric-sub"><?= esc($currentMonthLabel) ?></div></div>
            </div>

            <div class="data-card">
                <div class="data-card-head">
                    <h3>Demandes recentes</h3>
                    <a href="<?= site_url('/admin/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
                </div>
                <?php if ($recentRequests === []): ?>
                    <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande enregistree pour le moment.</p></div>
                <?php else: ?>
                    <table class="tbl">
                        <thead><tr><th>Employe</th><th>Type</th><th>Periode</th><th>Duree</th><th>Statut</th></tr></thead>
                        <tbody>
                            <?php foreach ($recentRequests as $request): ?>
                                <tr>
                                    <td class="td-name"><?= esc(trim(($request['prenom'] ?? '') . ' ' . ($request['nom'] ?? ''))) ?></td>
                                    <td><span class="type-badge <?= esc(adminDashboardTypeClass($request['type_libelle'])) ?>"><?= esc($request['type_libelle']) ?></span></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($request['date_debut']))) ?> - <?= esc(date('d/m/Y', strtotime($request['date_fin']))) ?></td>
                                    <td class="td-mono"><?= esc((string) $request['nb_jours']) ?> j</td>
                                    <td><span class="statut <?= esc(adminDashboardStatutClass($request['statut'])) ?>"><?= esc($request['statut']) ?></span></td>
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
