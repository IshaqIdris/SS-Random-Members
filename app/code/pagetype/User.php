<?php

namespace App\PageType;

use Page;

class User extends Page
{
    /**
     * Database name
     *
     * @var string
     */
    private static $table_name = "User";

    /**
     * Page description in CMS
     *
     * @var string
     */
    private static $description = "User";

    /**
     * Page database
     *
     * @var array
     */
    private static $db = [
    ];

    /**
     * Page relationship of 1 of each items
     *
     * @var array
     */
    private static $has_one = [
    ];

    /**
     * Determine ownership of asset to page in order to display
     *
     * @var array
     */
    private static $owns = [
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUser()
    {
        $content = file_get_contents('https://randomuser.me/api/');
        $obj = json_decode($content, true)['results'][0];
        $firstName = $obj['name']['first'];

        $user = ['firstname'=>$firstName];

        return $user;
        // var_dump($firstName);
    }
}
