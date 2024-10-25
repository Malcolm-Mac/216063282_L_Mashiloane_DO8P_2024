<?php

namespace App\Domain\User\Actions;

use App\Domain\User\Dto\UserData;
use App\Domain\User\Enums\RoleNames;
use App\Models\User;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        $user->update([
            'name' => $data->name,
            'surname' => $data->surname,
            'email' => $data->email
        ]);

        $user->syncRoles([$data->role]);

        $user->refresh();

        return $user;
    }
}
