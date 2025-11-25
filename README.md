🕵️ Who is the Impostor? (Ποιός είναι ο Impostor?)

A web-based social deduction party game built with Laravel 12 and Docker. This application automates the process of assigning roles and secret words to players via email, making it perfect for game nights.

🎮 Game Overview

Players enter their email addresses, and the application secretly assigns roles:

Crewmates: Receive an email with a secret word (e.g., "Pizza").

Impostor: Receives an email with a different secret word (e.g., "Burger") but is told they are the Impostor.

The goal is for the Crewmates to find the Impostor, while the Impostor tries to blend in without knowing the majority's word.

✨ Features

Dynamic Player Count: Supports 3 to 15 players.

Smart Impostor Scaling: Automatically calculates the number of Impostors based on the group size (1 Impostor per 3 players).

Massive Greek Word Bank: Includes over 500 thoroughly mixed Greek words across various categories (Animals, Food, Places, etc.) to ensure endless replayability.

Email Notifications: Sends formatted HTML emails via SMTP (Gmail) with role assignments.

Optional Timer: Built-in countdown timer to limit discussion time.

"Reveal" Functionality: A hidden button for the host to verify who the impostors were after the round ends.

Responsive UI: Built with Tailwind CSS, featuring a dark mode aesthetic and setup modals.

🛠️ Tech Stack

Framework: Laravel 12

Environment: Docker (Laravel Sail)

Frontend: Blade Templates + Tailwind CSS (v4) + Vanilla JS

Build Tool: Vite

Mail: SMTP (Gmail App Passwords)

🚀 Quick Start

Clone the repository:

git clone [https://github.com/yourusername/impostor-game.git](https://github.com/yourusername/impostor-game.git)
cd impostor-game


Start Docker (Sail):

./vendor/bin/sail up -d


Install Dependencies:

./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail npm run build


Configure Environment:
Copy .env.example to .env and configure your Gmail SMTP settings:

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls


Play:
Visit http://localhost in your browser.

Have fun playing! 🕵️‍♂️
