<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who is the Impostor?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0f172a; color: #e2e8f0; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 space-y-6 bg-slate-800 rounded-xl shadow-2xl border border-slate-700">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-red-500 tracking-wider uppercase">Impostor Game</h1>
            <p class="mt-2 text-slate-400">Παρακαλώ πληκτρολογήστε 3 emails.</p>
        </div>

        @if (session('status'))
            <div class="p-4 mb-4 text-sm text-green-400 bg-slate-900 rounded-lg border border-green-900" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-400 bg-slate-900 rounded-lg border border-red-900">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('game.start') }}" method="POST" class="space-y-4">
            @csrf

            @for ($i = 0; $i < 3; $i++)
            <div>
                <label class="block mb-1 text-sm font-medium text-slate-300">Email του παίχτη {{ $i + 1 }}</label>
                <input type="email" name="emails[]" required
                    class="bg-slate-900 border border-slate-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 placeholder-slate-600"
                    placeholder="player{{$i+1}}@example.com">
            </div>
            @endfor

            <button type="submit"
                class="w-full text-white bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 focus:ring-4 focus:outline-none focus:ring-red-900 font-bold rounded-lg text-sm px-5 py-3 text-center transition-all transform hover:scale-105 mt-6">
                Έναρξη παιχνιδιού
            </button>
        </form>
    </div>

</body>
</html>
