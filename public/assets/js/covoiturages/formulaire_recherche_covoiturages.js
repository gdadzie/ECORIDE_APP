function debounce(fn, delay = 250) {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

function setupAutocomplete(inputId, endpoint) {
    const input = document.getElementById(inputId);
    const list = input.parentNode.querySelector(".autocomplete-list");
    let currentIndex = -1;

    const fetchAndShow = debounce(async () => {
        const term = input.value.trim();
        if (term.length < 2) {
            list.innerHTML = "";
            list.style.display = "none";
            currentIndex = -1;
            return;
        }

        try {
            const res = await fetch(endpoint + "&term=" + encodeURIComponent(term));
            if (!res.ok) return;
            const data = await res.json();
            list.innerHTML = "";
            currentIndex = -1;

            if (!Array.isArray(data) || data.length === 0) {
                list.style.display = "none";
                return;
            }

            data.forEach(ville => {
                const div = document.createElement("div");
                div.classList.add("item");
                div.textContent = ville.nom;

                div.addEventListener("click", () => {
                    input.value = ville.nom;
                    list.style.display = "none";
                    currentIndex = -1;
                });

                list.appendChild(div);
            });

            list.style.display = "block";
        } catch (err) {
            console.error(err);
        }
    }, 200);

    input.addEventListener("input", fetchAndShow);

    // Navigation clavier avec remplissage automatique
    input.addEventListener("keydown", (e) => {
        const items = list.querySelectorAll(".item");
        if (items.length === 0) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % items.length;
            updateActive(items);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateActive(items);
        } else if (e.key === "Enter") {
            if (currentIndex >= 0) {
                e.preventDefault();
                items[currentIndex].click();
            }
        }
    });

    function updateActive(items) {
        items.forEach(item => item.classList.remove("active"));
        items[currentIndex].classList.add("active");
        input.value = items[currentIndex].textContent; // Remplissage automatique
        items[currentIndex].scrollIntoView({ block: "nearest" });
    }

    // Fermer la liste si clic en dehors
    document.addEventListener("click", (e) => {
        if (!list.contains(e.target) && e.target !== input) {
            list.style.display = "none";
            currentIndex = -1;
        }
    });
}

// Initialisation
document.addEventListener("DOMContentLoaded", () => {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("date_depart").setAttribute("min", today);

    setupAutocomplete("ville_depart", "index.php?entity=villes&action=auto_completion");
    setupAutocomplete("ville_arrivee", "index.php?entity=villes&action=auto_completion");

    const form = document.getElementById("form-recherche");
    const dateInput = document.getElementById("date_depart");
    form.addEventListener("submit", function(e) {
        if (dateInput.value < today) {
            e.preventDefault();
            alert("Veuillez sélectionner une date valide (aujourd’hui ou ultérieure).");
        }
    });
});

const form = document.getElementById("form-recherche");
const loader = document.getElementById("loader");

form.addEventListener("submit", function(e) {
    const today = new Date().toISOString().split("T")[0];
    const dateInput = document.getElementById("date_depart");

    if (dateInput.value < today) {
        e.preventDefault();
        alert("Veuillez sélectionner une date valide (aujourd’hui ou ultérieure).");
        return;
    }

    // Afficher le loader avant de soumettre le formulaire
    loader.style.display = "block";
});
