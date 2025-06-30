<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Hash;

/**
 * Sanctum token generator
 *
 */
class APILoginAction
{

    private $credential_name;
    private $identity;
    private $password;
    private $credential_type;
    /**
     * constructor
     *
     * @param String $credential_name   name of the authentication column in database table (e.g username,email,phone etc.)
     * @param String $identity   identity to be checked for validity of the user
     * @param String $password   password to be checked for authenticity of the user
     * @param String $credential_type   model of the authneticatable user
     *
     * @return APILoginAction   instance of APILoginAction
     */
    public function __construct(string $credential_name, string $identity, string $password, string $credential_type)
    {
        $this->credential_name = $credential_name;
        $this->identity = $identity;
        $this->password = $password;
        $this->credential_type = $credential_type;
    }

    /**
     * check the user, returns authentication token data array
     *
     * @param String $token_name   token_name
     *
     * @return Array   authentication token data
     */
    public function run($token_name, array $abilities = []): array
    {
        $login_response = [
            "token" => null,
            "code" => 401,
            "success" => false,
            "message" => null
        ];
        $user = $this->credential_type::where($this->credential_name, $this->identity)->first();

        if (!isset($user) || !$user) {
            $login_response["message"] = "User not found";
            $login_response["code"] = 404;

            return $login_response;
        }

        if ($user && $user->is_active === 0) {
            $login_response["message"] = "User is not active";

            return $login_response;
        }

        if (!Hash::check($this->password, $user->getAuthPassword())) {
            $login_response["message"] = "Password not match";
            return $login_response;
        }
        $login_response["user"] = $user;
        // dd($user->features);
        $features = $user->features;
        if($features){
            $feature_slug = $features->pluck('slug')->toArray();
            $login_response["features"] = $feature_slug;
        }

        if ($abilities) {
            $token = $user->createToken($token_name, $abilities)->plainTextToken;
        } else {
            $token = $user->createToken($token_name)->plainTextToken;
        }
        $login_response["token"] = $token;
        $login_response["code"] = 200;
        $login_response["success"] = true;
        $login_response["message"] = 'Authenticated';

        return $login_response;
    }
}
