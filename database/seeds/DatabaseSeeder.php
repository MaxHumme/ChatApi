<?php
use Illuminate\Database\Seeder;

use MaxHumme\ChatApi\Infrastructure\Orm\Message;
use MaxHumme\ChatApi\Infrastructure\Orm\User;

/**
 * Class DatabaseSeeder
 *
 * Seeds the database with fake data.
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->createUsers();
        $this->createMessagesFor($users);
    }

    /**
     * Creates fake messages for the given $users.
     *
     * @param MaxHumme\ChatApi\Infrastructure\Orm\User[] $users
     */
    private function createMessagesFor($users)
    {
        Message::truncate();

        // create a bunch of automatically generated messages that will be sent by each user
        $faker = Faker\Factory::create();
        foreach ($users as $fromUser) {
            // create a random amount of messages that this user will send
            $numMessages = mt_rand(0, 50);
            for ($i = 0; $i < $numMessages; $i++) {
                // grab a user (different from $fromUser) to send the message to
                do {
                    $toUser = $users[array_rand($users)];
                } while ($fromUser->id === $toUser->id);

                // create the message
                $message = new Message;
                $message->from_user_id = $fromUser->id;
                $message->to_user_id = $toUser->id;
                $message->body = $faker->realText(250);
                $message->save();
            }
        }
    }

    /**
     * Creates a bunch of users.
     *
     * @return MaxHumme\ChatApi\Infrastructure\Orm\User[]
     */
    private function createUsers()
    {
        User::truncate();

        // create some fake user data
        $userData = [
            [
                'username' => 'darthvader',
                'auth_token' => str_random(16),
                'first_name' => 'Darth',
                'last_name' => 'Vader'
            ],
            [
                'username' => 'thehoff',
                'auth_token' => str_random(16),
                'first_name' => 'David',
                'last_name' => 'Hasselhoff'
            ],
            [
                'username' => 'hillary',
                'auth_token' => str_random(16),
                'first_name' => 'Hillary',
                'last_name' => 'Clinton'
            ],
            [
                'username' => 'trump',
                'auth_token' => str_random(16),
                'first_name' => 'Donald',
                'last_name' => 'Trump'
            ]
        ];

        // create the users based on the fake user data
        $users = [];
        foreach ($userData as $user) {
            $users[] = User::create($user);
        }

        return $users;
    }
}
