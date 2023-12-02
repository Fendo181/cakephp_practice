<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Article
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
    ];
}