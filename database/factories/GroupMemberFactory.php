<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupMember>
 */
class GroupMemberFactory extends Factory
{
    protected $model = GroupMember::class;

    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'user_id'  => User::factory(),
        ];
    }
}
