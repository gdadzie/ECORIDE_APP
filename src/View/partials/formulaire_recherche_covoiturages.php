<head>
    <link rel="stylesheet" href="assets/css/covoiturage/covoiturages.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<form id="form-recherche" method="POST" action="index.php?entity=covoiturages&action=recherche_covoiturages" class="search-form formulaire-recherche">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Ville de départ</label>
            <input type="text" name="ville_depart" id="ville_depart" class="form-control" placeholder="Ex : Paris" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Ville d'arrivée</label>
            <input type="text" name="ville_arrivee" id="ville_arrivee" class="form-control" placeholder="Ex : Lyon" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date_depart" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Heure (optionnel)</label>
            <input type="time" name="heure_depart" class="form-control">
        </div>
    </div>
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-search me-2">
            <i class="bi bi-search"></i> Rechercher
        </button>
        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </button>
    </div>
</form>

<!-- Loader pour la recherche -->
<div id="loader" class="text-center my-3" style="display:none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
    <p>Recherche en cours...</p>
</div>


<!-- Conteneur des résultats -->
<div id="resultats" class="mt-5"></div>
<?php if (empty($covoiturages)): ?>
    <div class="alert alert-warning text-center">😕 Aucun covoiturage trouvé pour ces critères.</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($covoiturages as $c): ?>
            <div class="col-md-4 mb-3">
                <div class="card p-3 shadow-sm">
                    <h5><?= htmlspecialchars($c['ville_depart_nom']) ?> → <?= htmlspecialchars($c['ville_arrivee_nom']) ?></h5>
                    <p><strong>Date :</strong> <?= htmlspecialchars($c['date_depart']) ?></p>
                    <p><strong>Heure :</strong> <?= htmlspecialchars($c['heure_depart']) ?></p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($c['prix']) ?> €</p>
                    <p><strong>Places :</strong> <?= htmlspecialchars($c['nb_places']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    (function() {
        // Debounce util
        function debounce(fn, delay = 250) {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => fn(...args), delay);
            };
        }

        // Créer liste autocomplete
        function createList(input, items) {
            const existing = document.querySelector(`#${input.id}-list`);
            if (existing) existing.remove();
            if (!items.length) return;

            const list = document.createElement('div');
            list.id = `${input.id}-list`;
            list.className = 'list-group position-absolute shadow-sm';
            list.style.zIndex = 2000;

            const rect = input.getBoundingClientRect();
            list.style.top = (input.offsetTop + input.offsetHeight) + 'px';
            list.style.left = input.offsetLeft + 'px';
            list.style.width = rect.width + 'px';

            items.forEach(v => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = v;
                item.addEventListener('click', () => {
                    input.value = v;
                    list.remove();
                });
                list.appendChild(item);
            });

            input.parentNode.style.position = 'relative';
            input.parentNode.appendChild(list);
        }

        // Fetch villes
        async function fetchVilles(term) {
            if (!term || term.length < 2) return [];
            const url = `index.php?entity=covoiturages&action=auto_completion&term=${encodeURIComponent(term)}`;
            try {
                const res = await fetch(url, { method: 'GET', credentials: 'same-origin' });
                if (!res.ok) return [];
                const data = await res.json();
                return Array.isArray(data) ? data : [];
            } catch (err) {
                console.error('Autocomplete fetch error:', err);
                return [];
            }
        }

        // Lier input
        function bindInput(selector) {
            const input = document.querySelector(selector);
            if (!input) return;

            const handler = debounce(async () => {
                const term = input.value.trim();
                if (term.length < 2) {
                    const list = document.querySelector(`#${input.id}-list`);
                    if (list) list.remove();
                    return;
                }
                const items = await fetchVilles(term);
                createList(input, items);
            }, 200);

            input.addEventListener('input', handler);

            document.addEventListener('click', (e) => {
                const list = document.querySelector(`#${input.id}-list`);
                if (list && !e.target.closest(`#${input.id}-list`) && e.target !== input) list.remove();
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            bindInput('#ville_depart');
            bindInput('#ville_arrivee');
        });
    })();

    // AJAX pour la recherche covoiturages
    $(document).ready(function() {
        $('#form-recherche').on('submit', function(e) {
            e.preventDefault();
            $('#resultats').html('');
            $('#loader').show();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(html) {
                    $('#loader').hide();
                    $('#resultats').html(html);
                },
                error: function() {
                    $('#loader').hide();
                    $('#resultats').html('<div class="alert alert-danger">Erreur lors de la recherche.</div>');
                }
            });
        });
    });
</script>

