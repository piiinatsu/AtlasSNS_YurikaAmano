<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
// 以下、バリデーションロジックを追加するための記述
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registerView(Request $request){
        return view('auth.register');
        $validated = $request->validated();
    }

    public function register(Request $request){
        // postの場合の処理(②入力したデータをpostで受け取る)
        if($request->isMethod('post')){
            // リクエストを受け取る。リクエストに内容が入っていたら、ここに入る。
            // postで来ていたらtrueになる。
            // isMethodとは、HTTP動詞(get,post)が合っていたらtrueを渡す。

            $this->validator($request->all())->validate();
            // バリデーションのために追記

            $username = $request->input('username');
            $mail = $request->input('mail');
            $password = $request->input('password');
            // 受け取ったものを変数にはめている

            User::create([
                'username' => $username,
                'mail' => $mail,
                'password' => bcrypt($password),
            ]);
            // 変数をデータベースのカラムに登録していく処理

            $request->session()->put('username', $username);
            // ユーザー名をセッションに保存

            return redirect('added');
            // 登録後、addedのページにアクセスする処理
        }
        // getの場合の処理(①新規ユーザー画面を表示させる)
        // return view('auth.register');
        $validated = $request->validated();
    }

    public function added(){
        $username = session('username');
        // セッションからユーザー名を取得
        return view('auth.added', ['username' => $username]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|min:2|max:12',
            'mail' => 'required|string|email|min:5|max:40|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[a-zA-Z0-9]+$/',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[a-zA-Z0-9]+$/'
            ],
        ]);
    }
}
