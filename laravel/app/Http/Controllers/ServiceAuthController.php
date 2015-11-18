<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\OAuthClient;
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

            $user = $this->user->verifyUserExistanceByEmail($providerUser->email);
            if ($user === false){
                $user = $this->createUserFromProviderData($providerUser);
            }
            if ($user !== null){
                $this->createProviderAndAssociateWithUser($user, $providerUser, $providerName);
            }

            $oAuthClient = new OAuthClient();
            $oAuthClientInfo = $oAuthClient->getClientInfoByName(OAuthClient::THIRD_PARTY_CLIENT);

            if ($oAuthClientInfo !== false){
                return view('third_party_login.login')->with([
                    "providerName" => $providerName,
                    "authToken" => $providerUser->token,
                    "clientId" => $oAuthClientInfo->id,
                    "clientSecret" => $oAuthClientInfo->secret,
                    "message" => null,
                    'success' => true,
                ]);
            }
        } catch (\Exception $e) {}

        return response()->json([
            "providerName" => $providerName,
            "authToken" => null,
            "clientId" => null,
            "clientSecret" => null,
            "message" => "Erro: Não foi possivel logar com seu ".$providerName.'.',
            'success' => false,
        ]);
    }

    public function createUserFromProviderData($data){
        return $this->user->create([
            'name' => $data->name,
            'email' => $data->email,
            'foto' =>$data->avatar,
        ]);
    }

    public function createProviderAndAssociateWithUser($user, $data, $providerName){
        $providerData = $user->thirdPartyLogins()->where('service_name', '=', $providerName)->first();

        if ($providerData === null){
            $thirdPartyLogin = $this->thirdPartyLogins->create([
                'service_name' => $providerName,
                'service_id' => $data->id,
                'service_token' => $data->token,
            ]);

            $user->ThirdPartyLogins()->save($thirdPartyLogin);
        }else{
            $providerData->service_token = $data->token;
            $providerData->save();
        }
    }
}
