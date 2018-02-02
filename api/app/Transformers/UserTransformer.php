<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'photoUrl' => $user->getPhoto(),
            'units' => $user->units,
            'role' => ($user->type_id) ? $user->type->title : ''
        ];
    }
}