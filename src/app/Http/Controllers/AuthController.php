<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Domain\User\Services\CreateUserService;

class AuthController extends Controller
{
    private $createUserService;

    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
    }

    /**
     * ログイン画面表示
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * 管理者ログイン画面表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showAdminLogin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * ログイン処理
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            if (Auth::attempt($validatedData, $request->boolean('remember'))) {
                $request->session()->regenerate();

                if (Auth::user()->isAdmin()) {
                    return redirect()->intended('/attendance');
                } else {
                    return redirect()->intended(config('fortify.home'));
                }
            }

            return redirect()->back()->withErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * 新規登録画面表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }


    /**
     * 新規登録処理
     * 
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {

        try {
            $validatedData = $request->validated();
            $user = $this->createUserService->create($validatedData);

            Auth::login($user);

            return redirect('/email/verify');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    /**
     * メール認証確認画面表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showVerificationNotice(): View
    {
        return view('auth.verify-email');
    }

    /**
     * メール認証処理
     * 
     * @param Request $request
     * @param string $id
     * @param string $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request, $id, $hash): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('mypage.profile.show');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($request->user()));
        }

        return redirect()->route('mypage.profile.show')->with('verified', true);
    }

    /**
     * メール認証再送信処理
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendVerificationEmail(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(config('fortify.home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', '認証メールを再送信しました。');
    }
}
