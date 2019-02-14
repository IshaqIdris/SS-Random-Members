<?php

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SilverStripe\Dev\SapphireTest;
use GuzzleHttp\Handler\MockHandler;
use App\Controller\UserPageController;
use SilverStripe\Core\Injector\Injector;
use GuzzleHttp\Exception\RequestException;
use SilverStripe\Control\HTTPResponse_Exception;
use SilverStripe\Security\Member;

class UserTests extends SapphireTest
{
    protected $history;

    public function setUp()
    {
        parent::setUp();
        $this->history=[];
        $this->createMockMember();
        $this->createMockData();
    }

    /**
     * Used to create mock array
     *
     * @var array
     */
    protected $user_data;

    /**
     * Used to create mock data
     *
     * @var array
     */
    protected $data;

    /**
     * Used to create mock data
     *
     * @var array
     */
    protected $bad_data;

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

        $this->bad_data = [
            'name' => [
                'first' => 'Big',
                'last' => 'Shaq',
            ],
            'cell' => '+642 987 7654',
            'picture' => [
                'large' => 'https://i.kym-cdn.com/entries/icons/original/000/023/879/dilolhijakestorteddd.jpg'
            ],
        ];
    }

    public function createMockData()
    {
        $this->data = [
            'results' => [
                            [
                                'name' => [
                                'first' => 'Big',
                                'last' => 'Shaq',
                                ],
                                'cell' => '+642 987 7654',
                                'picture' => [
                                    'large' => 'https://i.kym-cdn.com/entries/icons/original/000/023/879/dilolhijakestorteddd.jpg'
                                ],
                                'email' => 'big.shaq@mansnothot.com',
                            ]
            ]
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
        $c = UserPageController::create();
        $c->setUserData($this->user_data);
        $c->randomUser();
        $member = $c->getMember();

        $this->assertEquals($member->FirstName, $this->user_data['name']['first']);
        $this->assertEquals($member->Surname, $this->user_data['name']['last']);
        $this->assertEquals($member->Email, $this->user_data['email']);
        $this->assertEquals($member->Cell, $this->user_data['cell']);
        $this->assertEquals($member->Photo, $this->user_data['picture']['large']);
        $this->assertNotNull($member->ID);

        $member->delete();
    }

    public function test300Error()
    {
        $this->setMockResponses(301);
        $this->setExpectedException(HTTPResponse_Exception::class);
        $c = UserPageController::create();
        $c->getUserData();
    }

    public function test400Error()
    {
        $this->setMockResponses(400);
        $this->setExpectedException(RequestException::class);
        $c = UserPageController::create();
        $c->getUserData();
    }

    public function test500Error()
    {
        $this->setMockResponses(500);
        $this->setExpectedException(RequestException::class);
        $c = UserPageController::create();
        $c->getUserData();
    }

    public function testDataResponse()
    {
        $this->setMockResponses('200', [], json_encode($this->data));
        $c = UserPageController::create();
        $result = $c->getUserData();
        $this->assertEquals($result, $this->data['results'][0]);
    }

    public function testGetUsers()
    {
        $c = UserPageController::create();
        $memberList = $c->getUsers();
        $this->assertEquals(sizeof($memberList), sizeof(Member::get()));
    }

    // /**
    //  * Member is created incorrectly
    //  *
    //  * @return void
    //  */
    // public function testBadMember()
    // {
    //     $c = UserPageController::create();
    //     $c->setUserData($this->bad_data);
    //     $c->randomUser();
    //     $member = $c->getMember();

    //     $this->setExpectedException(RequestException::class);

    //     $member->delete();
    // }
}
