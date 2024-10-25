<?php

namespace App\Domain\User\Actions;
use App\Domain\User\Dto\UserData;
use App\Domain\User\Enums\RoleNames;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        $password = Str::random();

        $user = User::create([
            'name' => $data->name,
            'surname' => $data->surname,
            'email' => $data->email,
            'password' => bcrypt($password)
        ]);

        if ($data->role === RoleNames::ADMIN->value && !empty($data->campaign_id)) {
                $user->campaigns()->sync($data->campaign_id);
        }

        $user->assignRole($data->role);

        /* Mail::to($user)
            ->send(new Welcome($user, $password)); */

        return $user;
    }
}
