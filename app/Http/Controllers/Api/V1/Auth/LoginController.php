<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\V1\LoginLog;
use App\Models\V1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            LoginLog::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'is_success' => 0,
                'x_forwarded_for' => $request->header('x_forwarded_for')
            ]);
            return response(['error' => 'invalid credentials'], 401);
        }

        // if (!$user->email_verified_at) {
        //     return response(['error' => 'Email is not verified'], 422);
        // }

        LoginLog::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'is_success' => 1,
            'x_forwarded_for' => $request->header('x_forwarded_for')
        ]);

        $expiration = now()->addDays(1);
        $account_type = 'user';
        if($user->is_admin === 1) {
            $account_type = 'admin';
        }
        $token = $user->createToken('kodypay', [$account_type], $expiration)->plainTextToken;

        $data = [
            'name' => $user->name,
            'token' => $token,
            'account_type' => $account_type
        ];

        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
