<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;

use Firebase\Auth\Token\Exception\InvalidToken;
use InvalidArgumentException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ユーザー登録とログイン処理
     */
    public function login(LoginRequest $request)
    {
        // クライアントから送られてきたIDトークンの解析
        try {
            $verifiedIdToken = $request->getVerifiedIdToken();
        } catch (InvalidToken $e) {
            abort(400, '無効なトークンです。');
        }
        catch (InvalidArgumentException $e) {
            abort(400, 'トークンを解析できませんでした。');
        }

        // ユーザー登録
        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $user = User::firstOrCreate(
            ['firebase_uid' => $firebaseUid],
            $request->getUserData($firebaseUid)
        );

        if ($user) {
            Auth::login($user);
            return response()->json(['user' => Auth::user()]);
        } else {
            return abort(401, 'ユーザーアカウントを取得できませんでした。');
        }

    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request) {
        Auth::logout();
        return response()->json(['message' => "ログアウトしました。"]);
    }

    /**
     * ログインしているかどうか判定する
     */
    public function check(Request $request) {
        $data = Auth::user() ? ["user" => Auth::user()] : ["message" => "ログインしていません。"];
        return response()->json($data);
    }
}
