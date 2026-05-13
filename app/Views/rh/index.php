<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="sidebar-logo-icon"><i class="bi bi-people"></i></div><div class="sidebar-brand-name">TechMada RH<span>Espace RH</span></div></div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= site_url('/rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= site_url('/rh/demandes') ?>" class="active"><i class="bi bi-inbox"></i> Demandes</a></li>
            <li><a href="<?= site_url('/rh/soldes') ?>"><i class="bi bi-clipboard-data"></i> Soldes employes</a></li>
        </ul>
        <div class="sidebar-user"><div class="s-user-row"><div class="avatar av-blue">MR</div><div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div><?= view('partials/logout_form') ?></div></div>
    </aside>
    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Validation des demandes</div>
                <div class="topbar-breadcrumb"><a href="<?= site_url('/rh/dashboard') ?>">Accueil</a><i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
            </div>
            <div class="topbar-actions">
                <span class="nav-badge alert"><?= esc($count_attente ?? 0) ?> en attente</span>
            </div>
        </div>
        <div class="content">
            <?php $errorRefusId = session()->getFlashdata('error_refus_id'); ?>
            <?php $errorRefusReason = session()->getFlashdata('error_refus_reason'); ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="data-card">
                <div class="data-card-head">
                    <h3>Demandes a traiter</h3>
                    <form method="get" action="<?= site_url('/rh/demandes') ?>" style="display:flex;gap:8px;flex-wrap:wrap">
                        <select name="departement" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
                            <option value="">Tous les departements</option>
                            <?php foreach (($departements ?? []) as $dept): ?>
                                <option value="<?= esc($dept['id']) ?>" <?= ($current_departement ?? '') == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="statut" class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
                            <option value="en_attente" <?= ($current_statut ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="approuvee" <?= ($current_statut ?? '') === 'approuvee' ? 'selected' : '' ?>>Approuvee</option>
                            <option value="refusee" <?= ($current_statut ?? '') === 'refusee' ? 'selected' : '' ?>>Refusee</option>
                            <option value="annulee" <?= ($current_statut ?? '') === 'annulee' ? 'selected' : '' ?>>Annulee</option>
                            <option value="toutes" <?= ($current_statut ?? '') === 'toutes' ? 'selected' : '' ?>>Toutes</option>
                        </select>
                        <button class="btn-secondary" type="submit" style="padding:6px 12px;font-size:.8rem">Filtrer</button>
                    </form>
                </div>
                <table class="tbl">
                    <thead><tr><th>Employe</th><th>Type</th><th>Periode</th><th>Duree</th><th>Solde</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php if (! empty($demandes)): ?>
                            <?php foreach ($demandes as $demande): ?>
                                <?php
                                    $reste = null;
                                    if ($demande['jours_attribues'] !== null) {
                                        $reste = (int) $demande['jours_attribues'] - (int) $demande['jours_pris'];
                                    }
                                ?>
                                <tr>
                                    <td class="td-name"><?= esc($demande['prenom'] . ' ' . $demande['nom']) ?><div class="td-muted" style="font-size:.75rem"><?= esc($demande['dept_nom'] ?? '-') ?></div></td>
                                    <td><span class="type-badge t-annuel"><?= esc($demande['libelle']) ?></span></td>
                                    <td class="td-muted"><?= esc(date('d/m/Y', strtotime($demande['date_debut']))) ?> - <?= esc(date('d/m/Y', strtotime($demande['date_fin']))) ?></td>
                                    <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
                                    <td>
                                        <?php if ($reste === null): ?>
                                            <span class="td-muted">-</span>
                                        <?php else: ?>
                                            <span style="font-family:'DM Mono',monospace;color:<?= $reste <= 0 ? 'var(--danger)' : ($reste <= 2 ? 'var(--warn)' : 'var(--success)') ?>">
                                                <?= esc($reste) ?> j
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (($demande['statut'] ?? '') === 'en_attente'): ?>
                                            <span class="statut s-attente">en attente</span>
                                        <?php elseif (($demande['statut'] ?? '') === 'approuvee'): ?>
                                            <span class="statut s-approuvee">approuvee</span>
                                        <?php elseif (($demande['statut'] ?? '') === 'refusee'): ?>
                                            <span class="statut s-refusee">refusee</span>
                                        <?php else: ?>
                                            <span class="statut s-annulee">annulee</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (($demande['statut'] ?? '') === 'en_attente'): ?>
                                            <div class="action-btns" style="align-items:center">
                                                <form method="post" action="<?= site_url('/rh/demandes/' . $demande['id'] . '/approuver') ?>">
                                                    <?= csrf_field() ?>
                                                    <button class="btn-sm btn-approve" type="submit"><i class="bi bi-check-lg"></i> Approuver</button>
                                                </form>
                                                <button class="btn-sm btn-refuse" type="button" data-refuse-toggle="<?= esc($demande['id']) ?>"><i class="bi bi-x-lg"></i> Refuser</button>
                                            </div>
                                        <?php else: ?>
                                            <span class="td-muted" style="font-size:.75rem">-</span>
                                        <?php endif; ?>
                                        <?php if (! empty($demande['commentaire_rh'])): ?>
                                            <div class="td-muted" style="font-size:.72rem;margin-top:6px"><?= esc($demande['commentaire_rh']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (($demande['statut'] ?? '') === 'en_attente'): ?>
                                    <?php $showRefus = ($errorRefusId !== null && (int) $errorRefusId === (int) $demande['id']); ?>
                                    <tr id="refus-row-<?= esc($demande['id']) ?>" style="display:<?= $showRefus ? '' : 'none' ?>">
                                        <td colspan="7">
                                            <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
                                                <?php if ($errorRefusReason === 'solde_introuvable'): ?>
                                                    <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Solde introuvable pour cet employe</h3>
                                                    <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
                                                        Impossible de traiter cette demande car aucun solde n'a ete trouve pour cet employe.
                                                    </div>
                                                    <div class="form-actions">
                                                        <button class="btn-secondary" type="button" data-refuse-cancel="<?= esc($demande['id']) ?>"><i class="bi bi-arrow-left"></i> Fermer</button>
                                                    </div>
                                                <?php else: ?>
                                                    <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus - <?= esc($demande['prenom'] . ' ' . $demande['nom']) ?></h3>
                                                    <div style="font-size:.875rem;color:var(--ink);margin-bottom:1rem">
                                                        Demande de <strong><?= esc($demande['nb_jours']) ?> jours</strong> du <?= esc(date('d/m/Y', strtotime($demande['date_debut']))) ?> au <?= esc(date('d/m/Y', strtotime($demande['date_fin']))) ?> · Type : <?= esc($demande['libelle']) ?><br>
                                                        <?php if ($reste !== null && $reste < (int) $demande['nb_jours']): ?>
                                                            <span style="font-size:.8rem;color:var(--danger)"><i class="bi bi-exclamation-triangle"></i> Solde insuffisant : <?= esc($reste) ?> jour disponible, <?= esc($demande['nb_jours']) ?> demandes.</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <form method="post" action="<?= site_url('/rh/demandes/' . $demande['id'] . '/refuser') ?>">
                                                        <?= csrf_field() ?>
                                                        <div class="f-group">
                                                            <label class="f-label">Commentaire pour l'employe (obligatoire)</label>
                                                            <textarea name="commentaire_rh" class="f-textarea" placeholder="Ex : Solde insuffisant, veuillez contacter les RH pour un conge sans solde." required></textarea>
                                                        </div>
                                                        <div class="form-actions">
                                                            <button class="btn-sm btn-refuse" type="submit" style="padding:9px 16px;font-size:.875rem"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
                                                            <button class="btn-secondary" type="button" data-refuse-cancel="<?= esc($demande['id']) ?>"><i class="bi bi-arrow-left"></i> Annuler</button>
                                                        </div>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty"><i class="bi bi-inbox"></i><p>Aucune demande pour ce filtre.</p></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>

<?= $this->endSection() ?>

<script>
document.querySelectorAll('[data-refuse-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-refuse-toggle');
        const row = document.getElementById(`refus-row-${id}`);
        if (row) {
            row.style.display = row.style.display === 'none' ? '' : 'none';
        }
    });
});
document.querySelectorAll('[data-refuse-cancel]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-refuse-cancel');
        const row = document.getElementById(`refus-row-${id}`);
        if (row) {
            row.style.display = 'none';
        }
    });
});
</script>
