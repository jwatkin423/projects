<?php

namespace App\Services;

use App\Models\SocialGoogleAccount;
use App\Models\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialGoogleAccountService {
    public function createOrGetUser(ProviderUser $providerUser) {
        /*$account = SocialGoogleAccount::whereProvider('google')
                      ->whereProviderUserId($providerUser->getId())
                      ->first();*/

        $account = User::where('email', $providerUser->email)
                          ->first();
        if ($account) {
            return $account;
        } else {
            return redirect('/login');
        }
    }
}
