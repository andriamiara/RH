<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>" class="active"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
            <li><a href="<?= site_url('/admin/departements') ?>"><i class="bi bi-building"></i> Departements</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Vue d'ensemble</div><div class="topbar-breadcrumb">Administration</div></div><div class="topbar-actions"><a href="<?= site_url('/admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employe</a></div></div>
        <div class="content">
            <div class="metrics">
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div><div class="metric-val"><?= esc((string) $employesActifs) ?></div><div class="metric-label">Employes actifs</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val">4</div><div class="metric-label">Demandes en attente</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div><div class="metric-val"><?= esc((string) $departementsCount) ?></div><div class="metric-label">Departements</div></div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
