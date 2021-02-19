<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Kreait\Firebase\Auth as FirebaseAuth;
use Firebase\Auth\Token\Exception\InvalidToken;
use InvalidArgumentException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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
     * ユーザー登録とログイン処理
     */
    public function login(Request $request)
    {
        $idToken = $request->input('id_token');
        try {
            $verifiedIdToken = $this->fAuth->verifyIdToken($idToken);
        } catch (InvalidToken $e) {
            return response()->json(['message' => 'The token is invalid'], 400);
        }
        catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'The token could not be parsed'], 400);
        }

        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $this->fAuth->getUser($firebaseUid);
        $user = User::firstOrCreate(
            ['firebase_uid' => $firebaseUid],
            [
                'name' => $firebaseUser->displayName,
                'email' => $firebaseUser->email,
                'icon_url' => $firebaseUser->photoUrl,
            ]
        );

        if ($user) {
            Auth::login($user);
            return response()->json(['user' => Auth::user()]);
        } else {
            return response()->json(['message' => "The user was not found"], 400);
        }

    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request) {
        Auth::logout();
        return response()->json(['message' => "Logged out"]);
    }

    /**
     * ログインしているかどうかを返す
     */
    public function check(Request $request) {
        $data = Auth::user() ? ["user" => Auth::user()] : ["message" => "Not logged in"];
        return response()->json($data);
    }
}
