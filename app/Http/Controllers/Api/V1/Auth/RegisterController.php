<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\V1\User;
use App\Models\V1\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);    
        try {
            $data = null;
            DB::transaction(function () use ($request, &$data) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' =>  bcrypt($request->password),
                    'slug' => Str::uuid()
                ]);

                Wallet::create([
                    'user_id' => $user->id,
                    'slug' => Str::uuid(),
                    'balance' => '0.00'
                ]);
                
                $data = $user;
            });
            return response(["status" => true, "message" => "Account created successfully", "data" => $data], 201);
        } catch(Exception $e) {
            $message = $e->getMessage();
            Log::error($message);
            return response(['error' => 'Something went wrong'], 500);  
        }
    }
}
