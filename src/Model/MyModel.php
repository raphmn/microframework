<?php

use App\Model\MainModel;

class MyModel extends MainModel
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }
}