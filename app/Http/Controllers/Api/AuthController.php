<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckPasswordResetToken;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\PasswordResetTokenRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'passwordReset', 'createAPasswordResetToken', 'checkPasswordResetToken']]);
        $this->guard = "api";
    }

    /**
     * Obter Token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth($this->guard)->attempt($credentials)) {

            return response()->json([
                'message' => 'Suas credenciais estão incorretas, por favor, tente novamente.',
                'errors' => null
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Informações do usuário logado.
     * @authenticated
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth($this->guard)->user());
    }

    /**
     * Atualizar token.
     * @authenticated
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        $user = Auth::user();

        $headers = [
            'Access-Control-Expose-Headers' => 'Authorization',
            'Authorization' => "Bearer $token"
        ];

        $userRepository = new UserRepository;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 43200,
            'user_logged' => $userRepository->show($user->id),
            'permissions' => UserRepository::getPermissionsUser($user)
        ], Response::HTTP_OK, $headers);
    }

    /**
     * @param PasswordResetTokenRequest $request
     * @return JsonResponse
     */
    public function createAPasswordResetToken(PasswordResetTokenRequest $request): JsonResponse
    {
        try {

            $userRepository = new UserRepository();
            $userRepository->createAPasswordResetToken($request->input('email'));

            return response()->json(['success' => 'Um e-mail com instruções de recuperação será enviado caso exista este e-mail.']);
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param CheckPasswordResetToken $request
     * @return JsonResponse|void
     */
    public function checkPasswordResetToken(CheckPasswordResetToken $request)
    {
        try {

            $userRepository = new UserRepository();
            $userRepository->checkPasswordResetToken($request->input('email'), $request->input('password_reset_token'));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function passwordReset(PasswordResetRequest $request): JsonResponse
    {
        try {
            $userRepository = new UserRepository();

            $userRepository->passwordReset(
                $request->input('email'),
                $request->input('password'),
                $request->input('password_reset_token')
            );

            $token = auth($this->guard)->attempt($request->only(['email', 'password']));

            return $this->respondWithToken($token);
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }
}
