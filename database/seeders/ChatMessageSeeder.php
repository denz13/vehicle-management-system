<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\tbl_chat_messages;
use App\Models\User;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for seeding
        $users = User::take(5)->get();
        
        if ($users->count() < 2) {
            $this->command->info('Need at least 2 users to seed chat messages. Skipping...');
            return;
        }

        $sampleMessages = [
            [
                'from_user_id' => $users[0]->id,
                'to_user_id' => $users[1]->id,
                'message' => 'Hello! How are you today?',
                'status' => 'sent'
            ],
            [
                'from_user_id' => $users[1]->id,
                'to_user_id' => $users[0]->id,
                'message' => 'I\'m doing great, thanks for asking!',
                'status' => 'delivered'
            ],
            [
                'from_user_id' => $users[0]->id,
                'to_user_id' => $users[2]->id,
                'message' => 'Can you help me with the vehicle reservation?',
                'status' => 'read'
            ],
            [
                'from_user_id' => $users[2]->id,
                'to_user_id' => $users[0]->id,
                'message' => 'Of course! What do you need help with?',
                'status' => 'sent'
            ],
            [
                'from_user_id' => $users[1]->id,
                'to_user_id' => $users[3]->id,
                'message' => 'Meeting reminder for tomorrow at 9 AM',
                'status' => 'delivered'
            ]
        ];

        foreach ($sampleMessages as $messageData) {
            tbl_chat_messages::create($messageData);
        }

        $this->command->info('Chat messages seeded successfully!');
    }
}
