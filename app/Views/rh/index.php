<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-people"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace RH</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>" class="active"><i class="bi bi-inbox"></i> Demandes</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-blue">MR</div><div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Validation des demandes</div><div class="topbar-breadcrumb"><a href="<?= site_url('/rh/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div></div></div>
        <div class="content">
            <div class="data-card">
                <div class="data-card-head"><h3>Demandes a traiter</h3></div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Type</th><th>Periode</th><th>Duree</th><th>Solde</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td class="td-name">Soa Rakoto</td><td><span class="type-badge t-annuel">Annuel</span></td><td class="td-muted">23/06 - 27/06/2025</td><td class="td-mono">5 j</td><td><span style="font-family:'DM Mono',monospace;color:var(--success)">18 j</span></td><td><span class="statut s-attente">en attente</span></td><td><div class="action-btns"><button class="btn-sm btn-approve">Approuver</button><button class="btn-sm btn-refuse">Refuser</button></div></td></tr>
                        <tr><td class="td-name">Tsiry Fidy</td><td><span class="type-badge t-maladie">Maladie</span></td><td class="td-muted">18/06 - 19/06/2025</td><td class="td-mono">2 j</td><td><span style="font-family:'DM Mono',monospace;color:var(--warn)">1 j</span></td><td><span class="statut s-attente">en attente</span></td><td><div class="action-btns"><button class="btn-sm btn-approve">Approuver</button><button class="btn-sm btn-refuse">Refuser</button></div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
