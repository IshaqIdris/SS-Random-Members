<?php

namespace App\PageType;
use Page;
use App\Controller\UserPageController;

class UserPage extends Page
{
    private static $controller_name = UserPageController::class;
}
