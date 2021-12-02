<?php

namespace App\Http\Controllers\Sctr;

use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\Adm; // Admin class
use App\Models\Customer;

// Customer class

class OAuthController extends Controller
{
    private function githubRedirect(string $callbackPath)
    {
        // overriding normal callback URL to ensure that user will be redirected
        // back correctly by GitHub to continue customer login flow
        $callbackUrl = URL::to($callbackPath);
        // need to use 'stateless()' method here since API routes don't involve
        // session middleware, and Socialite by default relies on Session. calling
        // 'stateless()' disables this reliance.
        $redirResp = Socialite::driver('github')
            ->stateless()
            ->with(['redirect_uri' => $callbackUrl])
            ->redirect();

        // return github login url, including 'redirect_uri' query parameter
        return response()->json(
            ['login_url' => $redirResp->getTargetUrl()]
        );
    }

    public function githubRedirectCustomer()
    {
        return self::githubRedirect(env('GITHUB_CALLBACK_PATH_CUSTOMER'));
    }

    public function githubRedirectAdmin()
    {
        return self::githubRedirect(env('GITHUB_CALLBACK_PATH_ADMIN'));
    }

    public function githubCallbackCustomer()
    {
        $user = Socialite::driver('github')->stateless()->user();

        // if customer exists in database table, update its token. if the customer
        // does _not_ exist, create it with the correct username and token
        Customer::updateOrCreate(
            ['username' => $user->getNickName()],
            ['token' => $user->token ]
        );

        // the OAuth token is attached in a cookie, which should be sent with
        // every following AJAX request from frontend. Note that the third
        // argument of the cookie method says when the cookie is to expire
        // in _minutes_ while the $user->expiresIn value is in _seconds_,
        // hence we divide by 60.
        return response(
            'Hej ' . $user->getNickName() . '! Du är nu inloggad via GitHub.
            Vänligen stäng den här fliken och återvänd till SCTR.'
        )->cookie('oauth_token', $user->token, $user->expiresIn / 60);
    }

    public function githubCallbackAdmin()
    {
        $user = Socialite::driver('github')->stateless()->user();

        $adminRecord = Adm::firstWhere('username', $user->getNickName());

        if (! $adminRecord) {
            return response(
                'Hej ' . $user->getNickName() . '! Du verkar inte vara en administratör.
                Vänligen stäng webbgränssnittet för administratörer och gå in på en av
                våra kundsidor istället.'
            );
        }

        $adminRecord->token = $user->token;
        $adminRecord->save();

        return response(
            'Hej ' . $user->getNickName() . '! Du är nu inloggad som admin via GitHub. 
            Vänligen stäng den här fliken och återvänd till admingränssnittet.'
        )->cookie('oauth_token', $user->token, $user->expiresIn / 60);
    }

    public function checkUserType(Request $req)
    {
        if (Customer::isCustomerReq($req)) {
            $customer = Customer::reqToCustomer($req);
            return response()->json([
                'user_type' => 'customer',
                'id' => $customer->id
            ]);
        }
        if (Adm::isAdmReq($req)) {
            $adm = Adm::reqToAdm($req);
            return response()->json([
                'user_type' => 'admin',
                'id' => $adm->id
            ]);
        }
        return response()->json([
            'user_type' => 'not_logged_in',
            'id' => 'none'
        ]);
    }
}
