<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\ThirdPartyLogins;
use App\User;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;

class ServiceAuthController extends Controller
{

    /**
     * @var User
     */
    protected $user;
    /**
     * @var ThirdPartyLogins
     */
    protected $thirdPartyLogins;

    public function __construct(User $user, ThirdPartyLogins $thirdPartyLogins)
    {
        $this->user = $user;
        $this->thirdPartyLogins = $thirdPartyLogins;
    }

    /**
     * Redirect the user to the Service authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($providerName)
    {
        return Socialite::driver($providerName)->redirect();
    }

    /**
     * Obtain the user information from Service page.
     *
     * @return Response
     */
    public function handleProviderData($providerName)
    {
        try {
            $providerUser = Socialite::driver($providerName)->user();
            $providerUser->providerName = $providerName;

            return view('third_party_login.login')->with([
                "providerName" => $providerName,
                "user" => json_encode($providerUser),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "providerName" => $providerName,
                'success' => false,
            ]);
        }
    }

    public function saveProviderData()
    {
        $data = request()->get('user');
        $userData = json_decode($data);

        $user = $this->user->verifyUserExistanceByEmail($userData->email);
        if ($user !== false){
            if(count($user->thirdPartyLogins()->where('service_name', '=', $userData->providerName)->get()) == 0){
                $this->associateProviderWithUser($user, $userData);
            }
        }else{
            $this->signUpWithProviderData($userData);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function signUpWithProviderData($data){
        $user = $this->user->create([
            'name' => $data->name,
            'email' => $data->email,
            'foto' =>$data->avatar,
        ]);

        $this->associateProviderWithUser($user, $data);
    }

    public function associateProviderWithUser($user, $data){
        $thirdPartyLogin = $this->thirdPartyLogins->create([
            'service_name' => $data->providerName,
            'service_id' => $data->id,
            'service_token' => $data->token,
        ]);

        $user->ThirdPartyLogins()->save($thirdPartyLogin);
    }
}
