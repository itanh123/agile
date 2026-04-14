<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\BuildsTableRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessagesSeeder extends Seeder
{
    use BuildsTableRows;

    public function run(): void
    {
        $table = 'messages';
        if (! $this->tableExists($table)) {
            return;
        }

        $faker = fake();
        $dialogs = [
            ['user' => 'Can I book grooming for tomorrow?', 'ai' => 'Yes, tomorrow has available slots for grooming.'],
            ['user' => 'My cat missed vaccination last month, what now?', 'ai' => 'Please book a checkup first and we can schedule the proper vaccine plan.'],
            ['user' => 'Do you have promo codes today?', 'ai' => 'You can try WELCOME10 for first-time bookings.'],
            ['user' => 'How long does spa service take?', 'ai' => 'Spa service typically takes around 60 to 90 minutes.'],
            ['user' => 'Can I upload pet progress images?', 'ai' => 'Yes, progress images can be added after booking confirmation.'],
            ['user' => 'What payment methods are supported?', 'ai' => 'We support cash, card, bank transfer, and e-wallet.'],
            ['user' => 'Can I reschedule my booking?', 'ai' => 'Rescheduling is allowed before service starts.'],
            ['user' => 'Is surgery service available on weekends?', 'ai' => 'Weekend surgery is limited, please contact staff for confirmation.'],
            ['user' => 'Do you handle aggressive dogs?', 'ai' => 'Yes, please add notes in booking so our staff can prepare.'],
            ['user' => 'Can I request the same groomer again?', 'ai' => 'Yes, mention the preferred groomer in your note.'],
        ];

        foreach ($dialogs as $pair) {
            $userId = $faker->randomElement(SeederState::$userIds['user'] ?? SeederState::$userIds['all'] ?? [null]);

            DB::table($table)->insert(
                $this->withTimestamps($table, [
                    'user_id' => $userId,
                    'sender_type' => 'user',
                    'sender' => 'user',
                    'content' => $pair['user'],
                    'message' => $pair['user'],
                ])
            );

            DB::table($table)->insert(
                $this->withTimestamps($table, [
                    'user_id' => $userId,
                    'sender_type' => 'ai',
                    'sender' => 'ai',
                    'content' => $pair['ai'],
                    'message' => $pair['ai'],
                ])
            );
        }
    }
}
