<?php namespace App\Repositories;

use App\RepositoryInterfaces\Main as MainRepositoryInterface;

class Main implements MainRepositoryInterface {
    protected int $per_page = 10;
}