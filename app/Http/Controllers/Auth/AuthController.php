<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use DB;
class AuthController extends Controller
{
    private $auth;

    public function __construct(Authenticate $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {   
        $account = $request->input('account');
        $password = $request->input('password');
        //debug(md5($password));
        $users = DB::select('select * from user where email = ? and password = ?', [$account, md5($password)]);
        if(sizeof($users) == 1 ) {
            if ($this->auth->setVerified()) {
                return $this->auth->redirect();
            }
        } else {
            return redirect('login')->with('login_status', '帳號或密碼錯誤');
        }
    }

    public function logout(Request $request)
    {   
        if ($this->auth->cleanVerified()) {
            return redirect('login');
        }
    }
}