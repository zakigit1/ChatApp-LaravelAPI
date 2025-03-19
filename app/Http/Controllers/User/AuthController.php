<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public UserServices $userservices;

    public function __construct(UserServices $userservice){
       $this->userservices = $userservice;
    }


    /**
     * Registers a user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
    */


    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();//just validate columns (radi yjib ghi columns ldrnalhom validation fl rules)

        
        $user = $this->userservices->createUser($data);

        $token = $user->createToken(User::USER_TOKEN);

        return $this->sendResponse([
            'userData'=>$user,
           'token'=> $token->plainTextToken,
        ],'sucess','User has been register successfully.');
    }

    /**
     * Logins a user
     *
     * @param LoginRequest $request
     * @return JsonResponse
    */

    public function login(LoginRequest $request){

        $isValid = $this->isValidCredential($request);// radi tjih array json man function isValidCredential

        if (!$isValid['success']) { //sucess value is false

            return $this->sendResponse(
                $isValid['message'],
                'failed', 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        }


        $user = $isValid['user'];

        $token = $user->createToken(User::USER_TOKEN);

        return $this->sendResponse([
            'userData' => $user,
            'token' => $token->plainTextToken
        ], 'success','Login successfully!');


    }

    /**
     * Validates user credential
     *
     * @param LoginRequest $request
     * @return array
    */

    private function isValidCredential(LoginRequest $request) : array
    {
        $data = $request->validated();

        // Find User :
        $user = User::where('email', $data['email'])->first();

        // Email Is Invalide :
        if ($user === null) {

            return [
                'success' => false,
                'message' => 'Invalid Credential'
            ];

        }

        // Password Is Valide :
        if (Hash::check($data['password'], $user->password)) {

            return [
                'success' => true,
                'user' => $user
            ];

        }

        // Password Is Invalide :
        return [
            'success' => false,
            'message' => 'Password is not matched',// hadi nrmlemt mndirhch ngolh bli crendtial is not match
        ];

    }


    /**
     * Logins a user with token
     *
     * @return JsonResponse
    */

    public function loginWithToken() : JsonResponse
    {
        return $this->sendResponse(auth()->user(),'success','login successfully!');
    }

    /**
     * Logouts a user
     *
     * @param Request $request
     * @return JsonResponse
     */


    ######## this doing deleted just the current token of user :
    // public function logout(Request $request) : JsonResponse
    // {
    //     $request->user()->currentAccessToken()->delete();//delete the current user authanticated token

    //     // return $this->success(null,'Logout successfully!');
    // }

    ######## this doing deleted all user token  :
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->sendResponse(
            null,
            '*', 
            'Logout successfully!'
        );
    }


}
