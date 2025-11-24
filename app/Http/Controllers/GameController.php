<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'emails' => 'required|array|min:3',
            'emails.*' => 'required|email|distinct',
        ]);

        $players = $request->input('emails');
        $playerCount = count($players);

        $impostorCount = max(1, floor($playerCount / 3));

        $wordBank = [
            // --- ANIMALS ---
            'Dog', 'Cat', 'Elephant', 'Lion', 'Tiger', 'Bear', 'Zebra', 'Giraffe', 'Monkey', 'Penguin',
            'Kangaroo', 'Panda', 'Wolf', 'Fox', 'Rabbit', 'Mouse', 'Hamster', 'Eagle', 'Owl', 'Shark',
            'Whale', 'Dolphin', 'Octopus', 'Spider', 'Snake', 'Lizard', 'Frog', 'Turtle', 'Butterfly', 'Bee',
            'Ant', 'Beetle', 'Scorpion', 'Crab', 'Lobster', 'Jellyfish', 'Starfish', 'Seahorse', 'Coral', 'Clam',
            'Camel', 'Donkey', 'Horse', 'Cow', 'Pig', 'Sheep', 'Goat', 'Chicken', 'Duck', 'Turkey',
            'Crocodile', 'Alligator', 'Gorilla', 'Chimpanzee', 'Koala', 'Platypus', 'Raccoon', 'Squirrel', 'Bat', 'Hedgehog',
            'Peacock', 'Parrot', 'Flamingo', 'Swan', 'Goose', 'Pigeon', 'Crow', 'Raven', 'Woodpecker', 'Ostrich',
            'Rhino', 'Hippo', 'Buffalo', 'Deer', 'Moose', 'Elk', 'Bison', 'Gazelle', 'Cheetah', 'Leopard',

            // --- FOOD & DRINK ---
            'Pizza', 'Burger', 'Sushi', 'Pasta', 'Salad', 'Apple', 'Banana', 'Orange', 'Grape', 'Strawberry',
            'Watermelon', 'Chocolate', 'Cake', 'Ice Cream', 'Cookie', 'Bread', 'Cheese', 'Milk', 'Coffee', 'Tea',
            'Juice', 'Soda', 'Beer', 'Wine', 'Chicken', 'Steak', 'Fish', 'Rice', 'Potato', 'Tomato',
            'Onion', 'Garlic', 'Pepper', 'Salt', 'Sugar', 'Butter', 'Oil', 'Vinegar', 'Sauce', 'Soup',
            'Sandwich', 'Toast', 'Pancake', 'Waffle', 'Egg', 'Bacon', 'Sausage', 'Ham', 'Beef', 'Pork',
            'Taco', 'Burrito', 'Nachos', 'Fries', 'Chips', 'Popcorn', 'Donut', 'Croissant', 'Muffin', 'Bagel',
            'Pineapple', 'Mango', 'Peach', 'Pear', 'Cherry', 'Lemon', 'Lime', 'Avocado', 'Coconut', 'Berry',
            'Carrot', 'Broccoli', 'Spinach', 'Corn', 'Peas', 'Beans', 'Mushroom', 'Pumpkin', 'Cucumber', 'Lettuce',

            // --- OBJECTS & HOUSEHOLD ---
            'Table', 'Chair', 'Bed', 'Sofa', 'Lamp', 'Computer', 'Phone', 'Television', 'Clock', 'Mirror',
            'Door', 'Window', 'Key', 'Lock', 'Carpet', 'Rug', 'Curtain', 'Pillow', 'Blanket', 'Sheet',
            'Cup', 'Glass', 'Plate', 'Bowl', 'Fork', 'Spoon', 'Knife', 'Bottle', 'Can', 'Box',
            'Bag', 'Backpack', 'Wallet', 'Purse', 'Umbrella', 'Comb', 'Brush', 'Toothbrush', 'Soap', 'Towel',
            'Shampoo', 'Perfume', 'Makeup', 'Razor', 'Scissors', 'Tape', 'Glue', 'Paper', 'Pen', 'Pencil',
            'Notebook', 'Book', 'Magazine', 'Newspaper', 'Letter', 'Envelope', 'Stamp', 'Calendar', 'Map', 'Globe',

            // --- TECHNOLOGY & TOOLS ---
            'Laptop', 'Tablet', 'Keyboard', 'Mouse', 'Monitor', 'Printer', 'Scanner', 'Camera', 'Microphone', 'Speaker',
            'Headphones', 'Earbuds', 'Charger', 'Battery', 'Cable', 'Wire', 'Switch', 'Plug', 'Socket', 'Bulb',
            'Fan', 'Heater', 'AC', 'Fridge', 'Oven', 'Microwave', 'Toaster', 'Blender', 'Mixer', 'Kettle',
            'Hammer', 'Screwdriver', 'Wrench', 'Pliers', 'Saw', 'Drill', 'Nail', 'Screw', 'Bolt', 'Nut',
            'Ladder', 'Shovel', 'Rake', 'Hoe', 'Axe', 'Chain', 'Rope', 'Bucket', 'Mop', 'Broom',

            // --- TRANSPORTATION ---
            'Car', 'Bus', 'Train', 'Airplane', 'Boat', 'Bicycle', 'Motorcycle', 'Scooter', 'Truck', 'Van',
            'Taxi', 'Ambulance', 'Police Car', 'Fire Truck', 'Tractor', 'Helicopter', 'Jet', 'Rocket', 'Spaceship', 'Submarine',
            'Ship', 'Yacht', 'Ferry', 'Canoe', 'Kayak', 'Skateboard', 'Rollerblades', 'Wheelchair', 'Stroller', 'Wagon',
            'Subway', 'Tram', 'Cable Car', 'Lift', 'Elevator', 'Escalator', 'Bridge', 'Tunnel', 'Road', 'Highway',

            // --- PROFESSIONS ---
            'Doctor', 'Nurse', 'Teacher', 'Student', 'Engineer', 'Lawyer', 'Police', 'Firefighter', 'Artist', 'Musician',
            'Actor', 'Chef', 'Farmer', 'Pilot', 'Driver', 'Mechanic', 'Electrician', 'Plumber', 'Carpenter', 'Scientist',
            'Astronaut', 'Soldier', 'Detective', 'Spy', 'Judge', 'King', 'Queen', 'Prince', 'Princess', 'Wizard',
            'Writer', 'Journalist', 'Photographer', 'Director', 'Designer', 'Programmer', 'Gamer', 'Athlete', 'Coach', 'Referee',

            // --- BUILDINGS & PLACES ---
            'House', 'Apartment', 'Hotel', 'School', 'University', 'Hospital', 'Clinic', 'Pharmacy', 'Bank', 'Post Office',
            'Library', 'Museum', 'Theater', 'Cinema', 'Stadium', 'Gym', 'Park', 'Zoo', 'Aquarium', 'Beach',
            'Forest', 'Mountain', 'River', 'Lake', 'Sea', 'Ocean', 'Desert', 'Jungle', 'Island', 'Cave',
            'Castle', 'Palace', 'Tower', 'Bridge', 'Church', 'Mosque', 'Synagogue', 'Temple', 'Shrine', 'Cathedral',
            'Farm', 'Barn', 'Factory', 'Warehouse', 'Office', 'Store', 'Shop', 'Mall', 'Market', 'Restaurant',

            // --- FANTASY & MYTHOLOGY ---
            'Dragon', 'Unicorn', 'Phoenix', 'Mermaid', 'Ghost', 'Vampire', 'Werewolf', 'Zombie', 'Alien', 'Robot',
            'Elf', 'Dwarf', 'Giant', 'Troll', 'Goblin', 'Witch', 'Wizard', 'Sorcerer', 'Fairy', 'Angel',
            'Demon', 'God', 'Goddess', 'Hero', 'Villain', 'Monster', 'Beast', 'Spirit', 'Soul', 'Magic',

            // --- CLOTHING ---
            'Shirt', 'Pants', 'Dress', 'Skirt', 'Shoe', 'Sock', 'Hat', 'Glove', 'Scarf', 'Jacket',
            'Coat', 'Belt', 'Tie', 'Ring', 'Necklace', 'Earring', 'Bracelet', 'Watch', 'Glasses', 'Sunglasses',
            'Boot', 'Sandal', 'Slipper', 'Heel', 'Sneaker', 'Uniform', 'Costume', 'Pajamas', 'Swimsuit', 'Bikini',

            // --- BODY PARTS ---
            'Head', 'Hair', 'Face', 'Eye', 'Ear', 'Nose', 'Mouth', 'Tooth', 'Tongue', 'Lip',
            'Neck', 'Shoulder', 'Arm', 'Elbow', 'Hand', 'Finger', 'Thumb', 'Chest', 'Stomach', 'Back',
            'Leg', 'Knee', 'Foot', 'Toe', 'Heart', 'Brain', 'Lung', 'Bone', 'Blood', 'Skin',

            // --- MUSIC & ART ---
            'Guitar', 'Piano', 'Drum', 'Violin', 'Flute', 'Trumpet', 'Saxophone', 'Harp', 'Cello', 'Clarinet',
            'Song', 'Melody', 'Rhythm', 'Beat', 'Lyrics', 'Note', 'Chord', 'Band', 'Orchestra', 'Concert',
            'Painting', 'Drawing', 'Sculpture', 'Photo', 'Movie', 'Play', 'Dance', 'Poem', 'Story', 'Book'
        ];

        // Pick 2 distinct random keys
        $randomKeys = array_rand($wordBank, 2);
        $crewWord = $wordBank[$randomKeys[0]];
        $impostorWord = $wordBank[$randomKeys[1]];

        $randomIndices = array_rand($players, $impostorCount);


        if (!is_array($randomIndices)) {
            $randomIndices = [$randomIndices];
        }


        foreach ($players as $index => $email) {

            // Check if current player index is in the list of impostors
            $isImpostor = in_array($index, $randomIndices);

            // Assign word
            $assignedWord = $isImpostor ? $impostorWord : $crewWord;

            // Send email
            Mail::to($email)->send(new RoleAssignment($isImpostor, $assignedWord));
        }

        return back()->with('status', "Το email στάλθηκε σε $playerCount παίκτες!");
    }
}
