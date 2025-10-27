<!-- Petit JS pour interactions (sidebar toggle, mock chart) -->

    // Sidebar toggle (mobile)
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('sidebar')?.classList.toggle('d-none');
});

    // Chart mockup
    const ctx = document.getElementById('tripsChart');
    if (ctx) {
    const chart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: Array.from({length: 14}, (_,i)=> `J-${13-i}`),
    datasets: [{
    label: 'Trajets',
    data: [12,18,15,22,19,24,30,28,26,32,35,31,37,40],
    tension: 0.3,
    fill: true,
    backgroundColor: 'rgba(40,167,69,0.08)',
    borderColor: '#28a745',
    pointRadius: 2
}]
},
    options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
    x: { grid: { display: false } },
    y: { ticks: { beginAtZero: true } }
}
}
});
}

    // Quick form demo
    document.getElementById('quickTripForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Création...';
    setTimeout(()=> {
    btn.disabled = false;
    btn.innerHTML = 'Créer';
    // show simple feedback
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.innerHTML = '<div class="toast show align-items-center text-bg-success border-0"><div class="d-flex"><div class="toast-body">Trajet créé (demo)</div><button type="button" class="btn-close btn-close-white ms-2 me-2"></button></div></div>';
    document.body.appendChild(toast);
    setTimeout(()=> toast.remove(), 3000);
}, 1000);
});
