<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\UserRequest;
use App\Services\RedisService;

use App\User;
use App\Models\Role;

class UserController extends Controller
{
    private $redisService;

    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
    }

    /**
     * ユーザー一覧の画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * 指定したユーザーの情報画面
     * @param App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 指定したユーザーの編集画面
     * @param App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        if (auth()->id() == $user->id || Gate::allows('isSystemAdmin')) {
            $roles = Role::all();
            $checkedRoles = $user->roles()->pluck('roles.id')->toArray();
            return view('users.edit', compact('user', 'roles', 'checkedRoles'));
        }
        abort(403);
    }

    /**
     * 指定したユーザーを更新
     * @param App\User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, UserRequest $request)
    {
        if (!(auth()->id() == $user->id || Gate::allows('isSystemAdmin'))) {
            return abort(403);
        }
        try {
            if ($request->roles !== $user->roles->pluck('id')->all()) {
                $this->redisService->clear('all_users_with_roles');
            }
            if (auth()->id() == $user->id) {
                $user->username = $request->username;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
            }
            $user->roles()->sync($request->roles);
        } catch (\Exception $exception) {
            return redirect()->route('users.show', ['user' => $user])->with('danger', '保存に失敗しました');
        }
        return redirect()->route('users.show', ['user' => $user])->with('success', '変更を保存しました');
    }

    /**
     * 指定したユーザーのパスワードを更新
     * @param App\User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(User $user, Request $request)
    {
        if (!(auth()->id() == $user->id || Gate::allows('isSystemAdmin'))) {
            return abort(403);
        }
        if (!password_verify($request->password, $user->password)) {
            return redirect()->route('users.edit', ['user' => $user])->with('danger', 'パスワードが違います');
        }
        if ($request->new_password !== $request->confirm_password) {
            return redirect()->route('users.edit', ['user' => $user])->with('danger', 'パスワードが一致しません');
        }
        try {
            if (auth()->id() == $user->id) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
        } catch (\Exception $exception) {
            return redirect()->route('users.edit', ['user' => $user])->with('danger', '保存に失敗しました');
        }
        return redirect()->route('users.show', ['user' => $user])->with('success', '変更を保存しました');
    }

    /**
     * 全てのユーザー取得のAPI
     * @return JsonResponse
     */
    public function getAll()
    {
        // $users = $this->redisService->get_and_set('all_users_with_roles', function () {
        //     return User::with('roles')->get();
        // });
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    /**
     * ユーザー検索用のAPI
     * @param App\User $editUser
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->query('query');
        $users = User::with('roles')->get()
            ->where('username', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')->get();
        return response()->json($users);
    }
}
