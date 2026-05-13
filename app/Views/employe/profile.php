<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= site_url('/employe/profil') ?>" class="active"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green"><?= esc(strtoupper(substr(($user['prenom'] ?? 'E'), 0, 1) . substr(($user['nom'] ?? 'M'), 0, 1))) ?></div><div><div class="user-name"><?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></div><div class="user-role">Employe</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Mon profil</div><div class="topbar-breadcrumb">Espace employe</div></div></div>
        <div class="content">
            <div class="form-section">
                <h3>Informations du compte</h3>
                <div class="form-grid-2">
                    <div><div class="f-label">Prenom</div><div class="f-input"><?= esc($user['prenom'] ?? '-') ?></div></div>
                    <div><div class="f-label">Nom</div><div class="f-input"><?= esc($user['nom'] ?? '-') ?></div></div>
                    <div><div class="f-label">Email</div><div class="f-input"><?= esc($user['email'] ?? '-') ?></div></div>
                    <div><div class="f-label">Departement</div><div class="f-input"><?= esc($departement['nom'] ?? '-') ?></div></div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
