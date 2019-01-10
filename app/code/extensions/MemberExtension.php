<?php

namespace App\Extensions;
use SilverStripe\ORM\DataExtension;

class MemberExtension extends DataExtension
{

    private static $db =
    [
        'Cell' => 'Text',
        'Photo' => 'Text'
    ];
}
