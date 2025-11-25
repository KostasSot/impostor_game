<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ποιός είναι ο Impostor?</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #0f172a; color: #e2e8f0; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <!-- SETUP MODAL -->
    <!-- We hide this if there are errors or a success status so the user can see them -->
    <div id="setup-modal" class="@if($errors->any() || session('status')) hidden @endif fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm fade-in">
        <div class="bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700 w-full max-w-sm text-center">
            <div class="mb-6">
                <div class="mx-auto bg-red-500/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Ρυθμίσεις Παιχνιδιού</h2>
                <p class="text-slate-400 text-sm mt-1">Πόσοι παίχτες θα παίξουν</p>
            </div>

            <div class="flex items-center justify-center space-x-4 mb-8">
                <button type="button" onclick="adjustCount(-1)" class="w-10 h-10 rounded-full bg-slate-700 hover:bg-slate-600 text-xl font-bold transition">-</button>
                <input type="number" id="modal-count" value="3" min="3" max="15" class="bg-transparent text-3xl font-bold text-center w-20 focus:outline-none text-red-500" readonly>
                <button type="button" onclick="adjustCount(1)" class="w-10 h-10 rounded-full bg-slate-700 hover:bg-slate-600 text-xl font-bold transition">+</button>
            </div>

            <button onclick="confirmSetup()" class="w-full bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold py-3 rounded-xl shadow-lg transform transition hover:scale-[1.02]">
                ΕΝΑΡΞΗ ΠΑΙΧΝΙΔΙΟΥ
            </button>
        </div>
    </div>

    <!-- GAME INTERFACE -->
    <!-- If Modal is showing, we hide this initially to keep it clean (via JS), unless we have errors/status -->
    <div id="game-container" class="@if(!$errors->any() && !session('status')) hidden @endif w-full max-w-lg p-8 space-y-6 bg-slate-800 rounded-xl shadow-2xl border border-slate-700 my-10 fade-in">
        <div class="text-center relative">
            <h1 class="text-3xl font-bold text-red-500 tracking-wider uppercase">Impostor Game</h1>
            <p class="mt-2 text-slate-400 text-sm font-mono" id="impostor-count-label">
                Calculating...
            </p>
            <!-- Reset Button -->
            <button onclick="location.reload()" class="absolute top-0 right-0 text-xs text-slate-500 hover:text-white underline">
                Επαναφορά
            </button>
        </div>

        @if (session('status'))
            <div class="p-4 text-sm text-green-400 bg-green-900/20 rounded-lg border border-green-900/50 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if (session('impostors'))
            <div class="text-center mt-2">
                <button type="button" onclick="toggleImpostor()" class="text-xs text-slate-500 hover:text-red-400 underline transition">
                    Εμφάνιση Impostor
                </button>
                <div id="impostor-reveal" class="hidden mt-3 p-3 bg-red-900/30 border border-red-900/50 rounded-lg text-red-300 text-sm animate-pulse">
                    <strong class="uppercase text-red-500">Impostor(s):</strong><br>
                    {{ implode(', ', session('impostors')) }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 text-sm text-red-400 bg-red-900/20 rounded-lg border border-red-900/50">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('game.start') }}" method="POST" class="space-y-4" onsubmit="disableButton()">
            @csrf

            <div id="players-container" class="space-y-3">
                {{--
                    LOGIC:
                    1. If validation failed (old inputs exist), show them.
                    2. If clean load (default), show 3 empty inputs.
                --}}
                @php
                    $currentEmails = old('emails', array_fill(0, 3, ''));
                @endphp

                @foreach($currentEmails as $index => $email)
                <div class="player-input group">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-500 group-hover:text-red-400 uppercase tracking-wide transition-colors">
                            Παίχτης {{ $index + 1 }}
                        </label>
                        @if($loop->count > 3)
                        <button type="button" onclick="this.closest('.player-input').remove(); updateLabel();" class="text-xs text-slate-600 hover:text-red-500">
                            &times; Αφαίρεση
                        </button>
                        @endif
                    </div>
                    <input type="email" name="emails[]" value="{{ $email }}" required
                        class="bg-slate-900 border border-slate-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-3 placeholder-slate-600 transition-all"
                        placeholder="player{{$index+1}}@example.com">
                </div>
                @endforeach
            </div>

            <!-- Controls -->
            <div class="pt-2 border-t border-slate-700 mt-4">
                <button type="button" onclick="addPlayerField()" class="w-full py-2 text-slate-400 hover:text-white hover:bg-slate-700 rounded-lg text-sm font-medium border border-dashed border-slate-600 hover:border-slate-400 transition-all">
                    + Προσθήκη παίχτη
                </button>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full text-white bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 focus:ring-4 focus:outline-none focus:ring-red-900 font-bold rounded-lg text-sm px-5 py-4 text-center transition-all transform hover:scale-[1.01] shadow-lg mt-6">
                Έναρξη παιχνιδιού
            </button>
        </form>
    </div>
</body>
</html>
