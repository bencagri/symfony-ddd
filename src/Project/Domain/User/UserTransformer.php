<?php

namespace App\Project\Domain\User;

use App\Project\Domain\User\Entity\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends  TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail()
        ];
    }
}