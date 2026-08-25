<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * 中間テーブルは belongsToMany のリレーション経由で操作する。
     *
     * @return void
     */
    public function run()
    {
        /** @var array<int, array<int, int>> フォローする側の ID => フォローされる側の ID */
        $relations = [
            1 => [2, 3],
            2 => [3],
        ];

        foreach ($relations as $userId => $followUserIds) {
            User::findOrFail($userId)->followings()->attach($followUserIds);
        }
    }
}
