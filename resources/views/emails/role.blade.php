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
 </style>
</head>
<body>
    <div class="container">
        <h2>Game Notification</h2>
        <p>The game has started. Your role is:</p>

        @if($isImpostor)
            <div class="role impostor">YOU ARE THE IMPOSTOR</div>
            <p>Your secret word is different from everyone else's:</p>
            <div class="word-box">{{ $assignedWord }}</div>
            <p>Try to blend in!</p>
        @else
            <div class="role crew">YOU ARE NOT THE IMPOSTOR</div>
            <p>Your secret word (shared with other crewmates) is:</p>
            <div class="word-box">{{ $assignedWord }}</div>
            <p>Find the person who has a different word!</p>
        @endif

        <div class="footer">
            Good luck.
        </div>
    </div>
</body>
</html>
