<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ArticlesTable
{
    public function initialize(array $config): void
    {
        $this->addBEhavior('Timestamp');
    }

}