<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-nav">
            <li><a href="<?= site_url('/employe/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes <span class="nav-badge alert">2</span></a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green">SR</div><div><div class="user-name">Soa Rakoto</div><div class="user-role">Employe · IT</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Tableau de bord</div><div class="topbar-breadcrumb">Accueil</div></div><div class="topbar-actions"><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('success')): ?><div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <div class="metrics">
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div><div class="metric-val">2</div><div class="metric-label">En attente</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div><div class="metric-val">5</div><div class="metric-label">Approuvees</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div><div class="metric-val">18</div><div class="metric-label">Jours restants</div><div class="metric-sub">sur 30 cette annee</div></div>
                <div class="metric"><div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div><div class="metric-val">1</div><div class="metric-label">Refusee</div></div>
            </div>
            <div class="data-card">
                <div class="data-card-head"><h3>Mes soldes de conges - 2025</h3></div>
                <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
                    <div class="solde-card" style="margin:0"><div class="solde-header"><span class="solde-type">Conge annuel</span><span class="solde-nums"><strong>18</strong> / 30 j</span></div><div class="solde-bar"><div class="solde-fill" style="width:60%"></div></div><div class="solde-label">18 jours restants · 12 pris</div></div>
                    <div class="solde-card" style="margin:0"><div class="solde-header"><span class="solde-type">Conge maladie</span><span class="solde-nums"><strong>8</strong> / 10 j</span></div><div class="solde-bar"><div class="solde-fill" style="width:80%"></div></div><div class="solde-label">8 jours restants · 2 pris</div></div>
                    <div class="solde-card" style="margin:0"><div class="solde-header"><span class="solde-type">Conge special</span><span class="solde-nums"><strong>1</strong> / 5 j</span></div><div class="solde-bar"><div class="solde-fill warn" style="width:20%"></div></div><div class="solde-label">1 jour restant · 4 pris</div></div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> - Projet CodeIgniter 4</div>
    </div>
</div>
<?= $this->endSection() ?>
