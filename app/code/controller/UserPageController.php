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
    private static $dependencies =
    [
        'client' => '%$GeneratedClient',
    ];

    /**
     * Actions
     *
     * @var array
     */
    // private static $allowed_actions =
    // [
    //     'randomUser'
    // ];


    /**
     * Get users
     *
     * @return void
     */
    public function getUsers()
    {
        var_dump(test);
        return Member::get();
    }

    /**
     * Get random user
     *
     * @return void
     */
    public function randomUser()
    {
        try {

            //look at convert class

            $response = $this->client->get('https://randomuser.me/api/')->getBody();
            $user_data = json_decode($response, true)['results'][0];
            $new_user = Member::create();
            $new_user->Firstname = Convert::raw2sql(ucfirst($user_data['name']['first']));
            $new_user->Surname = Convert::raw2sql(ucfirst($user_data['name']['last']));
            $new_user->Email = Convert::raw2sql($user_data['email']);
            $new_user->Cell = Convert::raw2sql($user_data['cell']);
            $new_user->Photo = Convert::raw2sql($user_data['picture']['large']);
            $new_user->write();

            var_dump('got into controller');

            return $this->customise(
                [
                    // 'FirstName' => ucfirst($user_data['name']['first']),
                    // 'LastName' => ucfirst($user_data['name']['last']),
                    // 'Email' => $user_data['email'],
                    // 'CellNo' => $user_data['cell'],
                    // 'LargePhoto' => $user_data['picture']['large'],
                    // 'MediumPhoto' => $user_data['picture']['medium'],
                    // 'SmallPhoto' => $user_data['picture']['thumbnail'],

                    'User' => $new_user,
                    'Test' => 'This is a test'
                 ]
            )->renderWith([static::class, Page::class]);

        } catch (RuntimeException $e) {
            echo 'Well thats a rip';
        }
    }
}
