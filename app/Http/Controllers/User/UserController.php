<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['verify']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
        $this->middleware('can:create,user')->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Auth::user());
        $users = User::all();
        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' =>'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
        $this->validate($request, $rules);

        $fields = $request->all();
        $fields['verified'] = User::USER_NOT_VERIFIED;
        $fields['verification_token'] = User::genVerificationToken();
        $fields['admin'] = User::USER_REGULAR;
        $fields['email_verified_at'] = null;

        $user = User::create($fields);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' =>'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USER_REGULAR . ',' . User::USER_ADMIN,
        ];
        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request->email) {
                $user->verified = User::USER_NOT_VERIFIED;
                $user->verification_token = User::genVerificationToken();
                $fields['email_verified_at'] = null;
                $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = $request->password;
        }
        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('Just verified users can change their status', 409);
            }
            $user->admin = $request->admin;
        }
        if (!$user->isDirty()) {
            return $this->errorResponse('Set at least a different value to update the user', 422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeUserStatus(Request $request, User $user)
    {
        $this->authorize('changeUserStatus', Auth::user());
        $rules = [
            'admin' => 'in:' . User::USER_REGULAR . ',' . User::USER_ADMIN,
        ];
        $this->validate($request, $rules);

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('Just verified users can change their status', 409);
            }
            $user->admin = $request->admin;
        }
        if (!$user->isDirty()) {
            return $this->errorResponse('Set at least a different value to update the user', 422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }

    /**
     * Verify if there is a user with the verification token pass by params and verify it
     * in case it is not
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::USER_VERIFIED;
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();
        return $this->showMessage('Account has been verified');
    }
}
