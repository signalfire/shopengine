<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Signalfire\Shopengine\Http\Requests\StoreLoginRequest;
use Signalfire\Shopengine\Http\Resources\TokenResource;
use Signalfire\Shopengine\Models\User;

class TokenController extends Controller
{
    public function store(StoreLoginRequest $request)
    {
        $validated = $request->validated();
        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                ['email' => ['The provided credentials are incorrect']],
            ]);
        }

        $token = $user->createToken($email);

        return (new TokenResource($token))
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(Request $request)
    {
        $request->user->tokens()->delete();

        return response()->json([], 202);
    }
}
