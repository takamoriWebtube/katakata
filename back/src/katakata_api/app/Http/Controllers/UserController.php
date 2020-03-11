<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\SignUpPost;
use App\Http\Requests\User\CreatePost;
use App\User;
use App\PreUser;
use App\Mail\User\SignUpMail;
use App\Mail\User\CreateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function signup(SignUpPost $request)
    {
        if(auth('api')->check()) {
            abort(400);
        }
        do {
            $urltoken = hash('sha256',uniqid(rand(),1));
        } while(PreUser::where('email', $request->email)->first());
        PreUser::create(['name' => $request->name, 'email' => $request->email, 'url_token' => $urltoken]);
        $url = config('app.url', 'https://localhost') . "/register?urltoken=" . $urltoken;
        Mail::to($request->email)->send(new SignUpMail($request, $url));
        return response()->json(['message' => 'ご登録ありがとうございます。登録されたメールアドレスに、本登録へのURLを送付いたしました。本登録へお進み下さい。'], 200);
    }

    public function checkUrlToken(Request $request)
    {
        if(auth('api')->check()) {
            abort(400);
        }
        $preuser = PreUser::where('url_token', $request->urltoken)->first();
        if (!$preuser) {
            abort(400);
        }
    }

    public function create(CreatePost $request)
    {
        if(auth('api')->check()) {
            abort(400);
        }
        $preuser = PreUser::where('url_token', $request->urltoken)->first();
        if (!$preuser) {
            abort(400);
        }
        if ($preuser->flag === 1) {
            abort(400);
        }
        DB::beginTransaction();
        try {
            User::create(['name' => $preuser->name, 'email' => $preuser->email, 'password' => Hash::make($request->password)]);
            PreUser::where('url_token', $request->urltoken)->update(['flag' => 1]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => '内部的なエラーが発生しました。再度やり直して下さい'], 400);
        }
        $user = User::where('email', $preuser->email)->first();
        
        Mail::to($preuser->email)->send(new CreateMail($preuser));
        return $this->publishToken($user->email, $request->password);
    }

    protected function signin(Request $request)
    {
        if(auth('api')->check()) {
            abort(400);
        }
        return $this->publishToken($request->email, $request->password);
    }

    protected function publishToken($email, $password) {
        if (! $token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json(['error' => '入力内容を確認して下さい'], 422);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ], 200);
    }

    protected function respondWithTokenbySNS($token, $user) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
        ], 200);
    }

    public function redirectToTwitter()
    {
        if(auth('api')->check()) {
            abort(400);
        }
        $redirectUrl = Socialite::driver('twitter')->redirect()->getTargetUrl();
        return response()->json([
            'redirect_url' => $redirectUrl
        ], 200);
    }

    public function handleTwitterCallback()
    {
        if(auth('api')->check()) {
            abort(400);
        }
        try {
            $socialUser = Socialite::driver('twitter')->user();
        } catch(\Exception $e) {
            abort(400, 'Oauthでエラーが発生しました');
        }
        $user = User::where(['email' => $socialUser->email])->first();
        if ($user) {
            User::where('email', $socialUser->email)->update(['twitter_id' => $socialUser->id, 'twitter_name' => $socialUser->nickname]);
            $token = JWTAuth::fromUser($user);
            return $this->respondWithTokenbySNS($token, $user);
        }
        User::create(['name' => $socialUser->nickname, 'email' => $socialUser->email, 'twitter_id' => $socialUser->id, 'twitter_name' => $socialUser->nickname]);
        $newUser = User::where(['email' => $socialUser->email])->first();
        $token = JWTAuth::fromUser($newUser);
        return $this->respondWithTokenbySNS($token, $newUser);
    }

    
}
