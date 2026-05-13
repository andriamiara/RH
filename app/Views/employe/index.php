<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green">SR</div><div><div class="user-name">Soa Rakoto</div><div class="user-role">Employe · IT</div></div></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Mes demandes de conge</div><div class="topbar-breadcrumb"><a href="<?= site_url('/employe/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div></div><div class="topbar-actions"><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a></div></div>
        <div class="content">
            <div class="data-card">
                <div class="data-card-head"><h3>Toutes mes demandes</h3></div>
                <table class="tbl">
                    <thead><tr><th>Type</th><th>Debut</th><th>Fin</th><th>Duree</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td><span class="type-badge t-annuel">Annuel</span></td><td class="td-muted">23 juin 2025</td><td class="td-muted">27 juin 2025</td><td class="td-mono">5 j</td><td><span class="statut s-attente">en attente</span></td><td class="td-muted">-</td><td><button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button></td></tr>
                        <tr><td><span class="type-badge t-maladie">Maladie</span></td><td class="td-muted">2 juin 2025</td><td class="td-muted">3 juin 2025</td><td class="td-mono">2 j</td><td><span class="statut s-approuvee">approuvee</span></td><td style="color:var(--success)">Valide</td><td><span class="td-muted">-</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
