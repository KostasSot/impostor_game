// --- MODAL LOGIC ---
window.adjustCount = function(change) {
    const input = document.getElementById('modal-count');
    let val = parseInt(input.value) + change;
    if (val >= 3 && val <= 15) input.value = val;
}

window.confirmSetup = function() {
    const count = parseInt(document.getElementById('modal-count').value);
    const container = document.getElementById('players-container');

    // Clear existing inputs
    container.innerHTML = '';

    // Generate new inputs
    for (let i = 1; i <= count; i++) {
        container.insertAdjacentHTML('beforeend', getPlayerHtml(i));
    }

    // Transition Views
    document.getElementById('setup-modal').classList.add('hidden');
    document.getElementById('game-container').classList.remove('hidden');

    updateLabel();
}

// --- GAME LOGIC ---
window.getPlayerHtml = function(index) {
    return `
        <div class="player-input group fade-in">
            <div class="flex items-center justify-between mb-1">
                <label class="text-xs font-bold text-slate-500 group-hover:text-red-400 uppercase tracking-wide transition-colors">
                    Παίχτης ${index}
                </label>
                <button type="button" onclick="this.closest('.player-input').remove(); updateLabel();" class="text-xs text-slate-600 hover:text-red-500">
                    &times; Αφαίρεση
                </button>
            </div>
            <input type="email" name="emails[]" required
                class="bg-slate-900 border border-slate-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-3 placeholder-slate-600 transition-all"
                placeholder="player${index}@example.com">
        </div>
    `;
}

window.addPlayerField = function() {
    const container = document.getElementById('players-container');
    const newIndex = container.querySelectorAll('.player-input').length + 1;
    container.insertAdjacentHTML('beforeend', getPlayerHtml(newIndex));
    updateLabel();
}

window.updateLabel = function() {
    const container = document.getElementById('players-container');
    if (!container) return;

    const count = container.querySelectorAll('.player-input').length;
    const impostors = Math.max(1, Math.floor(count / 3));

    const label = document.getElementById('impostor-count-label');
    if (label) {
        label.innerText = `Παίχτες: ${count} | Impostors: ${impostors}`;
    }
}

window.disableButton = function() {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-pulse">Αποστολή των email...</span>';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

window.toggleImpostor = function() {
    const el = document.getElementById('impostor-reveal');
    if (el) {
        el.classList.toggle('hidden');
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    updateLabel();
});
