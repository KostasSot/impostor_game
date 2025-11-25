// --- UTILS ---
// playSound removed as requested, but function kept empty to prevent errors if called
window.playSound = function(id) {
    // No sound
}

// --- MODAL LOGIC ---
window.adjustCount = function(change) {
    const input = document.getElementById('modal-count');
    let val = parseInt(input.value) + change;
    if (val >= 3 && val <= 15) input.value = val;
}

// --- TOGGLE TIMER VISIBILITY ---
window.toggleTimerInput = function() {
    const check = document.getElementById('timer-check');
    const container = document.getElementById('timer-input-container');
    if (check && container) {
        if (check.checked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
}

window.confirmSetup = function() {
    const count = parseInt(document.getElementById('modal-count').value);
    const container = document.getElementById('players-container');

    // Transfer Timer Settings to Main Form
    const timerCheck = document.getElementById('timer-check');
    const timerMinutes = document.getElementById('timer-minutes');

    if (timerCheck && timerCheck.checked) {
        document.getElementById('form-timer-enabled').value = "1";
        document.getElementById('form-timer-duration').value = timerMinutes.value;
    } else {
        document.getElementById('form-timer-enabled').value = "0";
        document.getElementById('form-timer-duration').value = "";
    }

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
        btn.innerHTML = '<span class="animate-pulse">Αποστολή των emails</span>';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// --- REVEAL IMPOSTOR ---
window.toggleImpostor = function() {
    const el = document.getElementById('impostor-reveal');
    if (el) {
        el.classList.toggle('hidden');
    }
}

// --- TIMER LOGIC ---
window.startTimer = function(minutes) {
    let time = minutes * 60;
    const display = document.getElementById('timer-display');

    if (!display) return;

    const interval = setInterval(() => {
        let m = Math.floor(time / 60);
        let s = time % 60;

        m = m < 10 ? "0" + m : m;
        s = s < 10 ? "0" + s : s;

        display.textContent = `${m}:${s}`;

        if (time <= 0) {
            clearInterval(interval);
            display.classList.add('text-red-600', 'animate-pulse');
            display.textContent = "00:00";
        }

        time--;
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    updateLabel();

    // Check if there is an active timer from the server
    const timerInput = document.getElementById('initial-timer-minutes');
    if (timerInput && timerInput.value) {
        const minutes = parseInt(timerInput.value);
        startTimer(minutes);

        // PERSISTENCE FIX: Update hidden form inputs so next round keeps the timer
        const formEnabled = document.getElementById('form-timer-enabled');
        const formDuration = document.getElementById('form-timer-duration');
        if (formEnabled) formEnabled.value = "1";
        if (formDuration) formDuration.value = minutes;

        // UI SYNC: Update the modal inputs to match
        const modalCheck = document.getElementById('timer-check');
        const modalInput = document.getElementById('timer-minutes');
        const modalContainer = document.getElementById('timer-input-container');

        if (modalCheck) modalCheck.checked = true;
        if (modalInput) modalInput.value = minutes;
        if (modalContainer) modalContainer.classList.remove('hidden');
    }
});
