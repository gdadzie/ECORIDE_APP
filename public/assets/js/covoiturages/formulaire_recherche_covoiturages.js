// ===============================
// Autocomplétion des villes
// ===============================

function debounce(fn, delay = 250) {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

function createList(input, items) {
    const existing = document.querySelector(`#${input.id}-list`);
    if (existing) existing.remove();
    if (!items.length) return;

    const list = document.createElement("div");
    list.id = `${input.id}-list`;
    list.className = "list-group position-absolute shadow-sm";
    list.style.zIndex = 2000;

    const rect = input.getBoundingClientRect();
    list.style.top = input.offsetTop + input.offsetHeight + "px";
    list.style.left = input.offsetLeft + "px";
    list.style.width = rect.width + "px";

    items.forEach(v => {
        const item = document.createElement("button");
        item.type = "button";
        item.className = "list-group-item list-group-item-action";
        item.textContent = v;
        item.addEventListener("click", () => {
            input.value = v;
            list.remove();
        });
        list.appendChild(item);
    });

    input.parentNode.style.position = "relative";
    input.parentNode.appendChild(list);
}

async function fetchVilles(term) {
    if (!term || term.length < 2) return [];
    const url = `index.php?entity=covoiturages&action=auto_completion&term=${encodeURIComponent(term)}`;
    try {
        const res = await fetch(url);
        if (!res.ok) return [];
        const data = await res.json();
        return Array.isArray(data) ? data : [];
    } catch {
        return [];
    }
}

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
    input.addEventListener("input", handler);

    document.addEventListener("click", (e) => {
        const list = document.querySelector(`#${input.id}-list`);
        if (list && !e.target.closest(`#${input.id}-list`) && e.target !== input) list.remove();
    });
}

document.addEventListener("DOMContentLoaded", () => {
    bindInput("#ville_depart");
    bindInput("#ville_arrivee");
});
