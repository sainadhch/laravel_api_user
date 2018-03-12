<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use App\UserTokensModel;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
	
	/**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'required|min:11|numeric|unique:users',
            'name' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
		
		$user = new User;
		$user->username = $request->get('username');
		$user->phone = $request->get('phone');
		$user->name = $request->get('name');
		$user->password = bcrypt($request->get('password'));
		$user->save();
		
		// Save token
		$user->user_token = auth()->tokenById($user->id);
		$user->save();
		
        return response([
			'status' => 'success',
			'data' => $user
		   ], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
		
		$this->updateUserToken($token);
		$input = [];
		$input['token'] = $token;
		\Auth::user()->tokens()->create($input);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
		$users = DB::table('users')->select('name', 'phone', 'user_token')->get();
		return response()->json(['users' => $users]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Log out all other users (Invalidate all other tokens).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout_other_sessions(Request $request)
    {
		$user = \Auth::user();
        $userTokens = UserTokensModel::where('user_id', $user->id)->get();
		$token = $request->get('token');;
		foreach ($userTokens as $userToken) {
			if($token != $userToken->token)
			{
				auth()->setToken($userToken->token)->invalidate();
				$userToken->delete(); 
			}
				
		}

        return response()->json(['message' => 'Successfully logged out All other Tokens']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
		$token = auth()->refresh();
		return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
	
	/**
     * Save user token.
     *
     * @param  string $token
     *
     * @return 
     */
	public function updateUserToken($token)
	{
		if($token)
		{
			$user = \Auth::user();
			$user->user_token = $token;
			$user->save();
		}
		return;
	}
}
