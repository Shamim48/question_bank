<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Round;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Option;
use App\Models\Ambassador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Student Users
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $students[] = User::create([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
                'class' => rand(8, 12),
                'group' => ['Science', 'Humanities', 'Commerce'][rand(0, 2)],
                'phone' => '017' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'division' => ['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Sylhet'][rand(0, 4)],
                'district' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Comilla', 'Bogura'][rand(0, 4)],
            ]);
        }

        // Create Rounds
        $round1 = Round::create(['name' => 'Round 1 — Preliminary', 'description' => 'Basic knowledge screening round', 'order' => 1, 'is_active' => true, 'is_final' => false]);
        $round2 = Round::create(['name' => 'Round 2 — Intermediate', 'description' => 'Advanced concepts and problem solving', 'order' => 2, 'is_active' => true, 'is_final' => false]);
        $round3 = Round::create(['name' => 'Round 3 — Final', 'description' => 'Final championship round with unique questions', 'order' => 3, 'is_active' => false, 'is_final' => true]);

        // Create Subjects for each round
        $subjects = [];
        foreach ([$round1, $round2, $round3] as $round) {
            foreach (['Mathematics', 'Science', 'General Knowledge'] as $subName) {
                $subjects[] = Subject::create([
                    'round_id' => $round->id,
                    'name' => $subName,
                    'description' => "$subName for {$round->name}",
                ]);
            }
        }

        // Create Questions with Options for each subject
        $sampleQuestions = [
            ['content' => 'What is 2 + 2?', 'options' => ['3', '4', '5', '6'], 'correct' => 2],
            ['content' => 'What is the capital of Bangladesh?', 'options' => ['Chattogram', 'Dhaka', 'Rajshahi', 'Sylhet'], 'correct' => 2],
            ['content' => 'Which planet is closest to the Sun?', 'options' => ['Venus', 'Earth', 'Mercury', 'Mars'], 'correct' => 3],
            ['content' => 'What is the largest ocean on Earth?', 'options' => ['Atlantic', 'Indian', 'Pacific', 'Arctic'], 'correct' => 3],
            ['content' => 'What is the chemical symbol for water?', 'options' => ['CO2', 'H2O', 'NaCl', 'O2'], 'correct' => 2],
            ['content' => 'Who wrote "Romeo and Juliet"?', 'options' => ['Dickens', 'Shakespeare', 'Hemingway', 'Austen'], 'correct' => 2],
            ['content' => 'What is the square root of 144?', 'options' => ['11', '12', '13', '14'], 'correct' => 2],
            ['content' => 'Which gas do plants absorb?', 'options' => ['O2', 'N2', 'CO2', 'H2'], 'correct' => 3],
            ['content' => 'How many continents are there?', 'options' => ['5', '6', '7', '8'], 'correct' => 3],
            ['content' => 'What is the speed of light?', 'options' => ['300,000 km/s', '150,000 km/s', '450,000 km/s', '600,000 km/s'], 'correct' => 1],
        ];

        foreach ($subjects as $subject) {
            foreach (array_slice($sampleQuestions, 0, rand(3, 6)) as $qData) {
                $question = Question::create([
                    'subject_id' => $subject->id,
                    'type' => 'text',
                    'content' => $qData['content'],
                    'time_limit' => rand(20, 60),
                    'points' => rand(1, 5),
                    'options_count' => 4,
                ]);

                foreach ($qData['options'] as $index => $optText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optText,
                        'option_number' => $index + 1,
                        'is_correct' => ($index + 1) === $qData['correct'],
                    ]);
                }

                // Attach to matching round
                $question->rounds()->attach($subject->round_id);
            }
        }

        // Create Ambassadors
        $divisions = ['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Rangpur', 'Barishal', 'Sylhet', 'Mymensingh'];
        $districts = ['Dhaka', 'Gazipur', 'Comilla', 'Bogura', 'Rajshahi', 'Khulna', 'Sylhet', 'Cox\'s Bazar'];
        $teams = ['Team Alpha', 'Team Beta', 'Team Gamma', 'Team Delta'];
        $events = ['Science Fair 2024', 'Quiz Championship', 'Math Olympiad', 'Debate Contest'];

        for ($i = 1; $i <= 20; $i++) {
            Ambassador::create([
                'name' => "Ambassador $i",
                'phone' => '018' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'team' => $teams[rand(0, count($teams) - 1)],
                'event' => $events[rand(0, count($events) - 1)],
                'division' => $divisions[rand(0, count($divisions) - 1)],
                'district' => $districts[rand(0, count($districts) - 1)],
            ]);
        }
    }
}
