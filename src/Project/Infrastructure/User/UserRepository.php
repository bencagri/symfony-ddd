<?php

namespace App\Project\Infrastructure\User;

use App\Project\Domain\User\Contract\UserRepositoryInterface;
use App\Project\App\Support\AppEntityRepository;

class UserRepository extends AppEntityRepository implements UserRepositoryInterface
{

    public function userDetails()
    {
        // TODO: Implement userDetails() method.
    }

    public function getUsers()
    {
        return $this->createQueryBuilder('u')->getQuery();
    }
}