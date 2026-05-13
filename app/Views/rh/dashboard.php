<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-people"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace RH</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/rh/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Demandes</a></li>
            <li><a href="<?= site_url('/rh/soldes') ?>"><i class="bi bi-clipboard-data"></i> Soldes employes</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-blue">MR</div><div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Tableau de bord RH</div><div class="topbar-breadcrumb">Responsable RH</div></div></div>
        <div class="content">
            <div class="metrics">
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val">4</div><div class="metric-label">Demandes en attente</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div><div class="metric-val">31</div><div class="metric-label">Traitees ce mois</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-exclamation-triangle"></i></div></div><div class="metric-val">2</div><div class="metric-label">Soldes critiques</div></div>
            </div>
            <div class="flash flash-info"><i class="bi bi-info-circle-fill"></i>Page RH statique extraite du template. La validation metier viendra ensuite.</div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
