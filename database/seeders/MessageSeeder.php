<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $sender) {
            $receiver = $users->where('UserID', '!=', $sender->UserID)->random();
            Message::factory()->create([
                'SenderID' => $sender->UserID,
                'ReceiverID' => $receiver->UserID,
            ]);
        }
    }
}
