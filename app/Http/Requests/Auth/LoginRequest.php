<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use Kreait\Firebase\Auth as FirebaseAuth;
use \Lcobucci\JWT\Token;

class LoginRequest extends FormRequest
{

    /**
     * @var FirebaseAuth
     */
    private $fAuth;

    public function __construct(FirebaseAuth $fAuth)
    {
        $this->fAuth = $fAuth;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * 検証済みのIDトークンを取得する
     * 
     * @return Token
     */
    public function getVerifiedIdToken()
    {
        $idToken = $this->input('id_token');
        return $this->fAuth->verifyIdToken($idToken);
    }

    /**
     * ユーザー情報を取得する
     * 
     * @return array
     */
    public function getUserData($firebaseUid)
    {
        $user = $this->fAuth->getUser($firebaseUid);
        return [
            'name' => $user->displayName,
            'email' => $user->email,
            'icon_url' => $user->photoUrl
        ];
    }
}
