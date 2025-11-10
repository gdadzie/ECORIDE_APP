<!-- Mes Covoiturages -->
<div class="tab-pane fade" id="mes-covoiturages">
    <h4>Mes Covoiturages</h4>
    <?php if (!empty($covoiturages)): ?>
        <ul>
            <?php foreach ($covoiturages as $c): ?>
                <li>
                    <strong><?= htmlspecialchars($c['titre'] ?? 'Covoiturage') ?></strong>
                    - Départ: <?= htmlspecialchars($c['ville_depart'] ?? '') ?>
                    - Arrivée: <?= htmlspecialchars($c['ville_arrivee'] ?? '') ?>
                    - Date: <?= htmlspecialchars($c['date'] ?? '') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun covoiturage enregistré.</p>
    <?php endif; ?>
</div>

