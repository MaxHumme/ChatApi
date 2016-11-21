<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\WebApiExtension\Context\WebApiContext;
use MaxHumme\ChatApi\Framework\BehatExtension\Context\App;
use MaxHumme\ChatApi\Infrastructure\Orm\Message;
use MaxHumme\ChatApi\Infrastructure\Orm\User;
use Faker\Factory as Faker;
use Illuminate\Contracts\Console\Kernel;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends WebApiContext  implements Context
{
    use App;

    private $faker;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->faker = Faker::create('en_EN');
    }

    /**
     * Refresh the database before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        // migrate
        app(Kernel::class)->call('migrate:refresh');
    }

    /**
     * @Given there are users:
     */
    public function thereAreUsers(TableNode $table)
    {
        $users = $table->getColumnsHash();

        foreach ($users as $userData) {
            $user = new User;
            $user->username = $userData['username'];
            $user->auth_token = $userData['auth_token'];
            $user->last_name = $userData['last_name'];
            $user->first_name = $userData['first_name'];
            $user->save();
        }
    }

    /**
     * @Given each user has :numOfMessages messages
     */
    public function eachUserHasMessages($numOfMessages)
    {
        $users = User::get()->all();

        // create messages for each user
        foreach ($users as $toUser) {
            // select a $fromUser that is not the same as $toUser
            do {
                $fromUser = $users[array_rand($users)];
            } while ($fromUser->id === $toUser->id);

            for ($i = 0; $i < $numOfMessages; $i++) {
                $message = new Message;
                $message->from_user_id = $fromUser->id;
                $message->to_user_id = $toUser->id;
                $message->body = $this->faker->text();
                $message->created_at = $this->faker->dateTimeBetween();
                $message->save();
            }
        }
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs($username)
    {
        $user = User::userWithUsername($username)->first();
        $this->removeHeader('Authorization');
        $this->addHeader('Authorization', $user->auth_token);
    }

    /**
     * Sends HTTP request to specific URL with json body from PyString.
     *
     * @param string       $method request method
     * @param string       $url    relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with json:$/
     */
    public function iSendARequestWithJson($method, $url, PyStringNode $string)
    {
        $this->iSetHeaderWithValue('Content-Type', 'application/json');
        $this->iSendARequestWithBody($method, $url, $string);
    }
}
