<!DOCTYPE html>
<html>
<head>
<style>
    body {
        font-family: sans-serif;
        background-color: #000;
        color: #fff;
        text-align: center;
        padding: 40px;
    }
    .container {
        border: 2px solid #333;
        padding: 40px;
        max-width: 600px;
        margin: 0 auto;
        border-radius: 10px;
    }
    .role {
        font-size: 32px;
        font-weight: bold;
        margin: 20px 0;
    }
    .word-box {
        background-color: #222;
        padding: 15px;
        font-size: 24px;
        border: 1px dashed #666;
        margin: 20px 0;
        color: #fff;
        text-align: center;
    }
    .impostor {
        color: #ff0000;
    }
    .crew {
        color: #00c3ff;
    }
    .footer {
        margin-top: 30px;
        color: #666;
        font-size: 12px;
    }
    .cheat-sheet {
        margin-top: 40px;
        border-top: 1px dashed #444;
        padding-top: 20px;
        color: #ff0000;
        font-size: 12px;
        text-align: left;
    }
 </style>
</head>
<body>
    <div class="container">
        <h2>Ειδοποίηση Παιχνιδιού</h2>
        <p>Το παιχνίδι ξεκίνησε. Ο ρόλος σου είναι:</p>

        @if($isImpostor)
            <div class="role impostor">ΕΙΣΑΙ Ο IMPOSTOR</div>
            <p>Δεν έχεις μυστική λέξη.</p>
            <p>Άκουσε τους άλλους, προσπάθησε να καταλάβεις τη λέξη τους και γίνε ένα με το πλήθος!</p>
        @else
            <div class="role crew">ΔΕΝ ΕΙΣΑΙ O IMPOSTOR</div>
            <p>Η μυστική λέξη (κοινή για τους υπόλοιπους) είναι:</p>
            <div class="word-box">{{ $assignedWord }}</div>
            <p>Βρες ποιος δεν ξέρει τη λέξη!</p>
        @endif

        <div class="footer">
            Καλή επιτυχία.
        </div>

        @if(isset($revealedImpostors) && $revealedImpostors)
            <div class="cheat-sheet">
                <span>Οι Impostors σε αυτόν τον γύρο είναι:</span><br>
                @foreach($revealedImpostors as $imp)
                    - {{ $imp }}<br>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
