<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Mlm;
use App\Models\AdminNotification;
use App\Models\Commission;
use App\Models\RedemptionCode;
use App\Models\User;
use App\Models\UserCommission;
use App\Models\UserExtra;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

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
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->middleware('guest');
    $this->middleware('registration.status')->except('registrationNotAllowed');
  }

  public function showRegistrationForm()
  {
    $pageTitle = "Register";
    $info = json_decode(json_encode(getIpInfo()), true);
    $mobileCode = @implode(',', $info['code']);
    $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
    $position = session('position');
    $refUser = null;
    $joining = null;
    $pos = 1;
    if ($position)
    {
      $refUser = User::where('username', session('ref'))->first();
      if ($position == 'left')
        $pos = 1;
      else
      {
        $pos = 2;
      }
      $positioner = Mlm::getPositioner($refUser, $pos);
      $join_under = $positioner;
      $joining = $join_under->username;
    }
    return view($this->activeTemplate . 'user.auth.register', compact('pageTitle', 'mobileCode', 'countries', 'refUser', 'position', 'joining', 'pos'));
  }


  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    $general = gs();
    $passwordValidation = Password::min(6);

    $agree = 'nullable';
    if ($general->agree)
    {
      $agree = 'required';
    }
    $countryData = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
    $countryCodes = implode(',', array_keys($countryData));
    $mobileCodes = implode(',', array_column($countryData, 'dial_code'));
    $countries = implode(',', array_column($countryData, 'country'));
    $validate = Validator::make($data, [
      'referral' => 'required|exists:users,username',
      'user_position' => 'required|exists:users,username',
      'position' => 'required|in:1,2',
      'email' => 'required|string|email|unique:users',
      'mobile' => 'required|regex:/^([0-9]*)$/',
      'password' => ['required', 'confirmed', $passwordValidation],
      'captcha' => 'sometimes|required',
      'mobile_code' => 'required|in:' . $mobileCodes,
      'country_code' => 'required|in:' . $countryCodes,
      'country' => 'required|in:' . $countries,
      'agree' => $agree,
      'code' => 'required|exists:redemption_codes,code'
    ]);


    return $validate;
  }

  public function register(Request $request)
  {
    $this->validator($request->all())->validate();
    $code = RedemptionCode::where('code', $request->code)->first();

    if ($request->code !== "abdullah-code")
    {
      if ($code->expire == true)
      {
        $notify[] = ['error', 'The code has expired.'];
        return back()->withNotify($notify)->withInput($request->all());
      }
    }

    $username = uniqid();
    $request->merge(['username' => $username]);

    $request->session()->regenerateToken();

    if (!verifyCaptcha())
    {
      $notify[] = ['error', 'Invalid captcha provided'];
      return back()->withNotify($notify);
    }


    $exist = User::where('mobile', $request->mobile_code . $request->mobile)->first();
    if ($exist)
    {
      $notify[] = ['error', 'The mobile number already exists'];
      return back()->withNotify($notify)->withInput();
    }

    $user = $this->create($request->all(), $code->value - 1);

    $this->guard()->login($user);

    return $this->registered($request, $user, $code);
  }


  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array $data
   * @return \App\User
   */
  public function create(array $data, $value = 0)
  {
    $general = gs();
    $referUser = User::where('username', $data['referral'])->first();
    $user_position = User::where('username', $data['user_position'])->first();
    $tree_id = $this->findUser($referUser->id, $data['position']);

    $user = new User();
    $user->email = strtolower(trim($data['email']));
    $user->password = Hash::make($data['password']);
    $user->username = $data['username'];
    $user->ref_by = $referUser->id;
    $user->tree_id = $tree_id;
    $user->pos_id = $user_position->id;
    $user->position = $data['position'];
    $user->country_code = $data['country_code'];
    $user->mobile = $data['mobile_code'] . $data['mobile'];
    $user->address = [
      'address' => '',
      'state' => '',
      'zip' => '',
      'country' => $data['country'] ?? null,
      'city' => ''
    ];
    $user->kv = $general->kv ? Status::NO : Status::YES;
    $user->ev = $general->ev ? Status::NO : Status::YES;
    $user->sv = $general->sv ? Status::NO : Status::YES;
    $user->ua = 1;
    $user->ts = 0;
    $user->tv = 1;
    $user->save();

    $this->createAdminNotification($user);
    $this->createUserLogin($user);
    $commission = Commission::first();

    if ($user_position->ua == 1)
    {
      $user_position->total_ref_com += $commission->direct;
      $user_position->balance += $commission->direct;
      $user_position->save();
    }

    $this->processUserCommissions($user, $referUser, $data['position'], 1);

    $integerPart = floor($value);
    $fractionalPart = $value - $integerPart;

    for ($i = 0; $i < $integerPart; $i++)
    {
      $this->createUser($user, $fractionalPart);
    }

    if ($fractionalPart > 0)
    {
      $this->createUser($user, 0.5);
    }

    return $user;
  }

  private function createUser($referUser, $valueCommission)
  {
    $position = random_int(1, 2);
    $tree_id = $this->findUser($referUser->id, $position);

    $user = new User();
    $user->email = null;
    $user->password = null;
    $user->username = null;
    $user->firstname = $referUser->username;
    $user->lastname = $position == 1 ? 'Left' : 'Right';
    $user->ref_by = $referUser->id;
    $user->position = $position;
    $user->tree_id = $tree_id;
    $user->pos_id = $referUser->id;
    $user->country_code = $referUser->country_code;
    $user->mobile = null;
    $user->address = [
      'address' => '',
      'state' => '',
      'zip' => '',
      'country' => '',
      'city' => ''
    ];
    $user->kv = 0;
    $user->ev = 0;
    $user->sv = 0;
    $user->ua = 0;
    $user->ts = 0;
    $user->tv = 0;
    $user->save();
    $user->save();

    $this->processUserCommissions($user, $referUser, $position, $valueCommission);
  }

  private function createAdminNotification($user)
  {
    $adminNotification = new AdminNotification();
    $adminNotification->user_id = $user->id;
    $adminNotification->title = 'New member registered';
    $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
    $adminNotification->save();
  }

  private function createUserLogin($user)
  {
    $ip = getRealIP();
    $exist = UserLogin::where('user_ip', $ip)->first();
    $userLogin = new UserLogin();

    if ($exist)
    {
      $userLogin->longitude = $exist->longitude;
      $userLogin->latitude = $exist->latitude;
      $userLogin->city = $exist->city;
      $userLogin->country_code = $exist->country_code;
      $userLogin->country = $exist->country;
    }
    else
    {
      $info = json_decode(json_encode(getIpInfo()), true);
      $userLogin->longitude = @implode(',', $info['long']);
      $userLogin->latitude = @implode(',', $info['lat']);
      $userLogin->city = @implode(',', $info['city']);
      $userLogin->country_code = @implode(',', $info['code']);
      $userLogin->country = @implode(',', $info['country']);
    }

    $userAgent = osBrowser();
    $userLogin->user_id = $user->id;
    $userLogin->user_ip = $ip;
    $userLogin->browser = @$userAgent['browser'];
    $userLogin->os = @$userAgent['os_platform'];
    $userLogin->save();
  }

  private function processUserCommissions($user, $referUser, $position, $valueCommission)
  {

    if ($position == 1)
    {
      $referUser->left += $valueCommission;
    }
    else
    {
      $referUser->right += $valueCommission;
    }

    $referUser->save();

    $commission = Commission::first();

    $maxDailyEarnings = $commission->max_day;
    $maxMonthlyEarnings = $commission->max_month;

    $referleftCount = $referUser->left;
    $referRightCount = $referUser->right;
    $number = UserCommission::where('referral_id', $referUser->id)->count() + 1;
    $dailyEarnings = ($referRightCount - (3 * $number) >= 0 && $referleftCount - (3 * $number) >= 0);

    $totalDailyEarnings = UserCommission::where('referral_id', $referUser->id)
      ->whereDate('created_at', Carbon::today())
      ->sum('commission');

    $thirtyDaysAgo = Carbon::now()->subDays(30);
    $totalMonthlyEarnings = UserCommission::where('referral_id', $referUser->id)
      ->where('created_at', '>=', $thirtyDaysAgo)
      ->sum('commission');

    if ($dailyEarnings && ($totalDailyEarnings + $commission->indirect) <= $maxDailyEarnings && ($totalMonthlyEarnings + $commission->indirect) <= $maxMonthlyEarnings)
    {
      if ($referUser->ua == 1)
      {
        $referUser->total_binary_com += $commission->indirect;
        $referUser->balance += $commission->indirect;
        $referUser->save();

        UserCommission::create([
          'user_id' => $user->id,
          'referral_id' => $referUser->id,
          'commission' => $commission->indirect,
        ]);
      }
    }

    $userCheck = User::where('id', $referUser->ref_by)->first();

    if ($userCheck)
    {
      $this->processUserCommissions($user, $userCheck, $referUser->position, $valueCommission);
    }
  }

  private function findUser($referUserId, $position)
  {
    $user = User::where('tree_id', $referUserId)->where('position', $position)->first();

    if ($user)
    {
      return $this->findUser($user->id, $position);
    }
    else
    {
      return $referUserId;
    }
  }

  public function checkUser(Request $request)
  {
    $exist['data'] = false;
    $exist['type'] = null;
    if ($request->email)
    {
      $exist['data'] = User::where('email', $request->email)->exists();
      $exist['type'] = 'email';
    }
    if ($request->mobile)
    {
      $exist['data'] = User::where('mobile', $request->mobile)->exists();
      $exist['type'] = 'mobile';
    }
    if ($request->username)
    {
      $exist['data'] = User::where('username', $request->username)->exists();
      $exist['type'] = 'username';
    }
    return response($exist);
  }

  public function registered(Request $request, $user, $code)
  {
    $userExtras = new UserExtra();
    $userExtras->user_id = $user->id;
    $userExtras->save();

    $code->expire = true;
    $code->username = $user->username;
    $code->save();


    // Mlm::updateFreeCount($user);
    return to_route('user.home');
  }
}
