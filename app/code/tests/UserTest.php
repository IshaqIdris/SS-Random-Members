<?php

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SilverStripe\Dev\SapphireTest;
use GuzzleHttp\Handler\MockHandler;
use App\Controller\UserPageController;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Control\HTTPResponse_Exception;

class UserTests extends SapphireTest
{
    protected $history;

    public function setUp()
    {
        parent::setUp();
        $this->history=[];
        $this->createMockMember();
    }

    /**
     * Used to create mock array
     *
     * @var array
     */
    protected $user_data;

    /**
     * Create mock member
     *
     * @return void
     */
    public function createMockMember()
    {
        $this->user_data = [
            'name' => [
                'first' => 'Big',
                'last' => 'Shaq',
            ],
            'cell' => '+642 987 7654',
            'picture' => [
                'large' => 'https://i.kym-cdn.com/entries/icons/original/000/023/879/dilolhijakestorteddd.jpg'
            ],
            'email' => 'big.shaq@mansnothot.com',
        ];
    }

    /**
     * Utility function to setup a mock client with responses.
     *
     * @param mixed $statusCode
     * @param mixed $body
     * @param mixed $headers
     */
    protected function setMockResponses($statusCode, $headers = [], $body = null)
    {
        $responses[] = new Response($statusCode, $headers, $body);
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($this->history));
        $client = new Client(['handler' => $handler]);
        // replace the default factory with our own
        Injector::inst()->registerService($client, 'GeneratedClient');
    }

    /**
     * Member is created correctly
     *
     * @return void
     */
    public function testUpdateMember()
    {
        var_dump($this->user_data);
        $c = UserPageController::create();
        $c->setUserData($this->user_data);
        $c->randomUser();
        $member = $c->getMember();
        var_dump($member->FirstName);

        $this->assertEquals($member->FirstName, $this->user_data['name']['first']);
        $this->assertEquals($member->Surname, $this->user_data['name']['last']);
        $this->assertEquals($member->Email, $this->user_data['email']);
        $this->assertEquals($member->Cell, $this->user_data['cell']);
        $this->assertEquals($member->Photo, $this->user_data['picture']['large']);
        $this->assertNotNull($member->ID);

        $member->delete();
    }

    public function testGetUserData()
    {
        $this->setMockResponses(301);
        $this->setExpectedException(HTTPResponse_Exception::class);
        $c = UserPageController::create();
        $c->getUserData();

    }
}
