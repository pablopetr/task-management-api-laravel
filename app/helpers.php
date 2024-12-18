<?php

use App\Models\User;

if(!function_exists('authorization')) {
    function authorization(User $user) {
        $token = auth()->login($user);

        return ['Authorization' => "Bearer $token"];
    }
}
