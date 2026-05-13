<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div><div class="sidebar-brand-name">TechMada RH<span>Administration</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/admin/dashboard') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="<?= site_url('/admin/employes') ?>" class="active"><i class="bi bi-people"></i> Employes</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar" style="background:#5a2d82">AD</div><div><div class="user-name">Administrateur</div><div class="user-role">Admin systeme</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Gestion des employes</div><div class="topbar-breadcrumb"><a href="<?= site_url('/admin/dashboard') ?>">Admin</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employes</div></div></div>
        <div class="content">
            <div class="form-section">
                <h3>Ajouter un employe</h3>
                <form method="post" action="#">
                    <?= csrf_field() ?>
                    <div class="form-grid-2">
                        <div class="f-group"><label class="f-label">Prenom</label><input class="f-input" value="Jean"></div>
                        <div class="f-group"><label class="f-label">Nom</label><input class="f-input" value="Rakoto"></div>
                        <div class="f-group"><label class="f-label">Email</label><input class="f-input" value="jean.rakoto@techmada.mg"></div>
                        <div class="f-group"><label class="f-label">Role</label><select class="f-select"><option>Employe</option><option>Responsable RH</option><option>Administrateur</option></select></div>
                    </div>
                    <div class="form-actions"><button class="btn-forest">Creer l'employe</button></div>
                </form>
            </div>
            <div class="data-card">
                <div class="data-card-head"><h3>Tous les employes</h3></div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Departement</th><th>Role</th><th>Statut</th></tr></thead>
                    <tbody>
                        <tr><td class="td-name">Soa Rakoto</td><td class="td-muted">IT</td><td><span class="type-badge">employe</span></td><td><span class="statut s-approuvee">actif</span></td></tr>
                        <tr><td class="td-name">Marie Rabe</td><td class="td-muted">RH</td><td><span class="type-badge t-maladie">rh</span></td><td><span class="statut s-approuvee">actif</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
