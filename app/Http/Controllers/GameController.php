<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Mail\RoleAssignment;

class GameController extends Controller
{
    public function index()
    {
        return view('game');
    }

    public function startGame(Request $request)
    {

        $request->validate([
            'emails' => 'required|array|min:3|max:3',
            'emails.*' => 'required|email|distinct',
        ]);

        $players = $request->input('emails');

        //api for words
        try {
            $response = Http::timeout(3)->get('https://random-word-api.herokuapp.com/word', [
                'number' => 2
            ]);

            if ($response->successful()) {
                $words = $response->json();
                $crewWord = ucfirst($words[0]);
                $impostorWord = ucfirst($words[1]);
            } else {
                throw new \Exception('API failed');
            }
        } catch (\Exception $e) {
            //fallback words
            $backups = [
                ['Pizza', 'Jupiter'],
                ['Bicycle', 'Philosophy'],
                ['Kangaroo', 'Laptop'],
                ['Banana', 'Tractor'],
                ['Ocean', 'Spaghetti'],
                ['Pyramid', 'Wifi'],
            ];
            $pair = $backups[array_rand($backups)];
            $crewWord = $pair[0];
            $impostorWord = $pair[1];
        }

        // 2. Select a random index to be the impostor (0, 1, or 2)
        $impostorIndex = array_rand($players);

        // 3. Loop through players and send emails
        foreach ($players as $index => $email) {

            // Determine role based on the random index
            $isImpostor = ($index === $impostorIndex);

            //assign the word to the email
            $assignedWord = $isImpostor ? $impostorWord : $crewWord;

            // Send the email
            Mail::to($email)->send(new RoleAssignment($isImpostor, $assignedWord));
        }

        return back()->with('status', "Το email στάλθηκε! Τσεκάρετε τα emails σας για να δείτε ποιός είναι ο Impostor.");
    }
}
