<?php

namespace App\Aurora\Infrastructure\User;

use App\Aurora\Domain\User\Contract\UserRepositoryInterface;
use App\Aurora\App\Support\AppEntityRepository;

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