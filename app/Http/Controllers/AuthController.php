<?php

namespace App\Http\Controllers;

use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Kreait\Firebase\Auth;
use App\Models\User;

class  AuthController extends Controller
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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $id_token = $request->input('idToken');
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($id_token);
        } catch (InvalidToken $e) {
            return response()->json(['message' => 'The token is invalid'], 400);
        }
        catch (InvalidArgumentException $e) {
            return response()->json(['message' => 'The token could not be parsed'], 400);
        }

        $firebase_uid = $verifiedIdToken->headers()->get('sub');
        $firebase_user = $this->auth->getUser($firebase_uid);
        User::firstOrCreate(
            ['firebase_uid' => $firebase_uid],
            [
                'name' => $firebase_user->displayName,
                'email' => $firebase_user->email,
                'icon_url' => $firebase_user->photoUrl,
                'is_Admin' => false,
            ]
        );

        return response()->json(['message' => "logged in"]);

    }
}
