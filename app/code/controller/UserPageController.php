<?php

namespace App\Controller;
use Page;
use PageController;
use GuzzleHttp\Client;
use App\Model\UserData;
use SilverStripe\Core\Convert;
use SilverStripe\Security\Member;
use function GuzzleHttp\json_decode;
use SilverStripe\Control\Email\Email;
use Intervention\Image\Exception\RuntimeException;

class UserPageController extends PageController
{
    private $user_data;

    private $new_user;

    public function init()
    {
        var_dump("hello");
        parent::init();
        //$this->json_decode(setUserData($this->client->get('https://randomuser.me/api/')->getBody(), true)['results'][0]);
    }

    public function getUserData()
    {
        try {
            $response = $this->client->get('https://randomuser.me/api/');
        } catch (Exception $e) {
            $this->httpError($e->getCode(), $e->getMessage() );
        }


        if($response->getStatusCode() != 200) {
            var_dump("Hello");
            $this->httpError($response->getStatusCode(), $response->getReasonPhrase() );
        }


        $data = json_decode($response->getBody(), true)['results'][0];

        if (empty($data)) {
            throw new Exception("No Data");
        }

        return $data;
    }

    private static $dependencies =
    [
        'client' => '%$GeneratedClient',
    ];

    /**
     * Actions
     *
     * @var array
     */
    private static $allowed_actions =
    [
        'randomUser'
    ];


    /**
     * Get users
     *
     * @return array
     */
    public function getUsers()
    {

        return Member::get();
    }

    /**
     * Get created user
     *
     * @return Member
     */
    public function getMember()
    {
        return $this->new_user;
    }

    /**
     * Set data to be read for users
     *
     * @param array $data
     * @return void
     */
    public function setUserData($data)
    {
            $this->user_data = $data;
    }

    /**
     * Get random user
     *
     * @return void
     */
    public function randomUser()
    {
        var_dump("got in generate users");
        try {
            //look at convert class
            $new_user = Member::create();
            $new_user->FirstName = Convert::raw2sql(ucfirst($this->user_data['name']['first']));
            $new_user->Surname = Convert::raw2sql(ucfirst($this->user_data['name']['last']));
            $new_user->Email = Convert::raw2sql($this->user_data['email']);
            $new_user->Cell = Convert::raw2sql($this->user_data['cell']);
            $new_user->Photo = Convert::raw2sql($this->user_data['picture']['large']);
            $new_user->write();
            $this->new_user = $new_user;

            return $this->customise(
                [
                    'User' => $this->getUsers(),
                    'Test' => 'This is a test'
                 ]
            )->renderWith([static::class, Page::class]);

        } catch (Exception $e) {
            echo 'Well thats a rip';
        }
    }
}
