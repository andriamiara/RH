<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green">SR</div><div><div class="user-name">Soa Rakoto</div><div class="user-role">Employe · IT</div></div></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Nouvelle demande de conge</div><div class="topbar-breadcrumb"><a href="<?= site_url('/employe/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande</div></div></div>
        <div class="content">
            <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">
                <div>
                    <div class="form-section">
                        <h3>Details de la demande</h3>
                        <form method="post" action="#">
                            <?= csrf_field() ?>
                            <div class="f-group"><label class="f-label">Type de conge</label><select class="f-select"><option>Conge annuel (18 j restants)</option><option>Conge maladie (8 j restants)</option><option>Conge special (1 j restant)</option><option>Sans solde</option></select></div>
                            <div class="form-grid-2"><div class="f-group"><label class="f-label">Date de debut</label><input type="date" class="f-input" value="2025-06-23"></div><div class="f-group"><label class="f-label">Date de fin</label><input type="date" class="f-input" value="2025-06-27"></div></div>
                            <div class="f-computed"><div class="f-computed-num">5</div><div class="f-computed-label">jours calendaires calcules<br><span style="font-size:.7rem;opacity:.7">du lundi 23 au vendredi 27 juin 2025</span></div></div>
                            <div class="f-group"><label class="f-label">Motif</label><textarea class="f-textarea" placeholder="Precisez le motif..."></textarea></div>
                            <div class="form-actions"><button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button><a href="<?= site_url('/employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a></div>
                        </form>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem">
                    <div class="data-card" style="margin:0"><div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div><div style="padding:.75rem 1.1rem">Conge annuel: 18 j<br>Maladie: 8 j<br>Special: 1 j</div></div>
                    <div class="flash flash-info" style="margin:0"><i class="bi bi-info-circle-fill"></i><span style="font-size:.8rem">Page extraite du template, statique pour le moment.</span></div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>
