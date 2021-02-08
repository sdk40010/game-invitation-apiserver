<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Kreait\Firebase\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * firebaseのIDトークンを検証する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $idToken = $request->input('idToken');
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
        } catch (InvalidToken $e) {
            return response()->json(['message' => 'The token is invalid'], 400);
        }
        catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'The token could not be parsed'], 400);
        }

        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $this->auth->getUser($firebaseUid);
        $user = User::firstOrCreate(
            ['firebase_uid' => $firebaseUid],
            [
                'name' => $firebaseUser->displayName,
                'email' => $firebaseUser->email,
                'icon_url' => $firebaseUser->photoUrl,
            ]
        );

        if ($user) {
            return response()->json(['message' => "logged in"]);
        } else {
            return response()->json(['message' => "The user was not found"], 400);
        }
    }
}
