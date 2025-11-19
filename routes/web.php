<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [GameController::class, 'index']);
Route::post('/play-game', [GameController::class, 'startGame'])->name('game.start');

// --- TEST ROUTE ---
// Usage: http://localhost:80/test-mail?email=your-other-email@gmail.com
Route::get('/test-mail', function () {
    $email = request('email');

    if (!$email) {
        return 'Please provide an email in the URL like this: <br>
                <a href="http://localhost:80/test-mail?email=your_email@gmail.com">http://localhost:80/test-mail?email=your_email@gmail.com</a>';
    }

    try {
        Mail::raw('If you are reading this, your Laravel SMTP configuration is working perfectly!', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Laravel SMTP Test');
        });

        return "Success! Test email sent to: <strong>$email</strong>. <br>Check your inbox (and spam folder).";
    } catch (\Exception $e) {
        return "Error sending email: " . $e->getMessage();
    }
});
