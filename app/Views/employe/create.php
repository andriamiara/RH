<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$selectedType = (int) old('type_conge_id', $soldes[0]['id'] ?? 0);
$selectedSolde = null;
foreach ($soldes as $solde) {
    if ($solde['id'] === $selectedType) {
        $selectedSolde = $solde;
        break;
    }
}
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= site_url('/employe/demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= site_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-green">SR</div><div><div class="user-name">Soa Rakoto</div><div class="user-role">Employe · IT</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar"><div><div class="topbar-title">Nouvelle demande de conge</div><div class="topbar-breadcrumb"><a href="<?= site_url('/employe/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande</div></div></div>
        <div class="content">
            <?php if (session()->getFlashdata('error')): ?><div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
            <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">
                <div>
                    <div class="form-section">
                        <h3>Details de la demande</h3>
                        <form method="post" action="<?= site_url('/employe/demandes') ?>">
                            <?= csrf_field() ?>
                            <div class="f-group" style="margin-bottom:1rem">
                                <label class="f-label">Type de conge <span style="color:var(--danger)">*</span></label>
                                <select name="type_conge_id" id="type_conge_id" class="f-select">
                                    <?php foreach ($soldes as $solde): ?>
                                        <option value="<?= esc($solde['id']) ?>" data-restants="<?= esc($solde['jours_restants']) ?>" data-attribues="<?= esc($solde['jours_annuels']) ?>" data-pris="<?= esc($solde['jours_pris']) ?>" data-deductible="<?= esc($solde['deductible']) ?>" <?= $selectedType === $solde['id'] ? 'selected' : '' ?>>
                                            <?= esc($solde['libelle']) ?> (<?= esc($solde['jours_restants']) ?> j restants)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('errors.type_conge_id')): ?><div class="f-error"><?= esc(session('errors.type_conge_id')) ?></div><?php endif; ?>
                            </div>

                            <div class="form-grid-2" style="margin-bottom:1rem">
                                <div class="f-group">
                                    <label class="f-label">Date de debut <span style="color:var(--danger)">*</span></label>
                                    <input type="date" id="date_debut" name="date_debut" class="f-input" value="<?= esc(old('date_debut')) ?>">
                                    <?php if (session('errors.date_debut')): ?><div class="f-error"><?= esc(session('errors.date_debut')) ?></div><?php endif; ?>
                                </div>
                                <div class="f-group">
                                    <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                                    <input type="date" id="date_fin" name="date_fin" class="f-input" value="<?= esc(old('date_fin')) ?>">
                                    <?php if (session('errors.date_fin')): ?><div class="f-error"><?= esc(session('errors.date_fin')) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="f-computed" id="computed-box">
                                <div class="f-computed-num" id="computed-days">0</div>
                                <div class="f-computed-label" id="computed-label">jours calendaires calcules<br><span style="font-size:.7rem;opacity:.7" id="computed-range">Selectionnez une periode.</span></div>
                            </div>

                            <div class="f-group" style="margin-bottom:1rem">
                                <label class="f-label">Motif (optionnel)</label>
                                <textarea name="motif" class="f-textarea" placeholder="Precisez le motif de votre demande si necessaire..."><?= esc(old('motif')) ?></textarea>
                                <?php if (session('errors.motif')): ?><div class="f-error"><?= esc(session('errors.motif')) ?></div><?php endif; ?>
                            </div>

                            <div class="form-actions">
                                <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
                                <a href="<?= site_url('/employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem">
                    <div class="data-card" style="margin:0">
                        <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes <?= esc((string) $referenceYear) ?></h3></div>
                        <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
                            <?php foreach ($soldes as $solde): ?>
                                <?php $percent = $solde['jours_annuels'] > 0 ? max(0, min(100, ($solde['jours_restants'] / $solde['jours_annuels']) * 100)) : 0; ?>
                                <div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                        <span style="font-size:.8rem;color:var(--ink)"><?= esc($solde['libelle']) ?></span>
                                        <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:<?= $solde['jours_restants'] <= 2 && $solde['deductible'] ? 'var(--warn)' : 'var(--forest)' ?>;font-weight:500"><?= esc($solde['jours_restants']) ?> j</span>
                                    </div>
                                    <div class="solde-bar"><div class="solde-fill<?= $solde['jours_restants'] <= 2 && $solde['deductible'] ? ' warn' : '' ?>" style="width:<?= esc((string) $percent) ?>%"></div></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flash flash-info" style="margin:0"><i class="bi bi-info-circle-fill"></i><span style="font-size:.8rem">Le nombre de jours est calcule en JavaScript pour l'affichage et recalcule cote serveur a la soumission.</span></div>
                    <div class="flash flash-warn" style="margin:0"><i class="bi bi-exclamation-triangle-fill"></i><span style="font-size:.8rem">Les demandes qui chevauchent une periode deja demandee ou qui depassent le solde sont refusees.</span></div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<script>
const typeSelect = document.getElementById('type_conge_id');
const dateDebut = document.getElementById('date_debut');
const dateFin = document.getElementById('date_fin');
const computedDays = document.getElementById('computed-days');
const computedRange = document.getElementById('computed-range');

function formatFrenchDate(value) {
    const date = new Date(`${value}T00:00:00`);
    return new Intl.DateTimeFormat('fr-FR', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(date);
}

function updateComputedDays() {
    if (!dateDebut.value || !dateFin.value) {
        computedDays.textContent = '0';
        computedRange.textContent = 'Selectionnez une periode.';
        return;
    }

    const start = new Date(`${dateDebut.value}T00:00:00`);
    const end = new Date(`${dateFin.value}T00:00:00`);

    if (end < start) {
        computedDays.textContent = '0';
        computedRange.textContent = 'La date de fin doit etre posterieure a la date de debut.';
        return;
    }

    const diff = Math.floor((end - start) / 86400000) + 1;
    computedDays.textContent = String(diff);
    computedRange.textContent = `du ${formatFrenchDate(dateDebut.value)} au ${formatFrenchDate(dateFin.value)}`;
}

dateDebut.addEventListener('input', updateComputedDays);
dateFin.addEventListener('input', updateComputedDays);
updateComputedDays();
</script>
<?= $this->endSection() ?>
