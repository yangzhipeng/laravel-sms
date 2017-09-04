<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
// use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SmsController extends Controller
{

    // public function sendCode(Request $request)
    // {
    //     require_once base_path('vendor/yunpian-sdk-php/YunpianAutoload.php');

    //     $num         = rand(1000, 9999);
    //     $smsOperator = new \SmsOperator();
    //     $mobil       = $request->input('phone');

    //     $data['mobile']    = $mobil;
    //     $data['tpl_id']    = "1";
    //     $data['tpl_value'] =
    //     urlencode("#code#") . "="
    //     . urlencode($num) . "&"
    //     . urlencode("#company#") . "="
    //     . urlencode("**科技");
    //     $result = $smsOperator->tpl_send($data);
    //     Session::put('vercode', $num);
    //     print_r($result);
    //     return Response::make("ok", 200);
    // }

    public function registerSend(Request $request)
    {
        $rphone = $request->input('phone');
        if (!($this->check_phone($rphone))) {
            // $user = User::where('mobile', '=', $rphone)->first();
            // if ($user->isused == 0) {
            $user           = new User;
            $user->name     = "text";
            $user->email    = "tse0sss0st@qq.com";
            $user->password = "123456";
            $user->ctime    = time();
            $user->mid      = 1;
            $user->type     = 0;
            $user->mobile   = $rphone;
            $user->code     = rand(1000, 9999);
            $app            = "MyApp";
            $user->times    = 9;
            $user->content  = "【**科技】感谢您注册" . $app . "，您的验证码是" . $user->code;
            $user->save();
            return $this->sendCode($rphone, $user->content);
        } else {
            $ret['err']     = 1;
            $ret['msg']     = 'phone has registered';
            $ret['content'] = $rphone;

            return $ret;
        }
        // Log::info("会员注册信息是:", $result);
    }

    public function findPasswordSend(Request $request)
    {
        $fphone = $request->input('phone');
        if ($this->check_phone($fphone)) {
              $smscount = User::where('type', 1)->whereBetween('ctime', array(Carbon::today()->timestamp, Carbon::today()->addHours(24)->timestamp))->count();
              if ($smscount < 4) {
                $user           = new User;
                $user->name     = "text";
                $user->email    = "sdssd@qq.com";
                $user->password = "123456";
                $user->mid      = 1;
                $user->code     = rand(1000, 9999);
                $user->ctime    = time();
                $user->type     = 1;
                $user->times    = 9;
                $user->content  = "【**科技】您好，您正在进行找回密码操作，切勿将验证码泄露于他人。验证码" . $user->code;
                $user->save();
                return $this->sendCode($fphone, $user->content);
            } else {
                $ret['err']     = 1;
                $ret['msg']     = 'one day most 3 times';
                $ret['content'] = $fphone;

                return $ret;
            }
        } else {
            $ret['err']     = 1;
            $ret['msg']     = 'The phone has not regiested or none';
            $ret['content'] = $fphone;

            return $ret;
        }
    }

    public function bindindPhoneSend(Request $request)
    {
        $bphone = $request->input('phone');
        if ($this->check_phone($bphone)) {
            $user = User::where('mobile', '=', $bphone)->first();
            if ($user->type != 2) {
                $users           = new User;
                $users->name     = "text";
                $users->email    = "00ss55@qq.com";
                $users->password = "123456";
                $users->ctime    = time();
                $users->mid      = 1;
                $users->type     = 0;
                $users->mobile   = $bphone;
                $users->times    = 10;
                $users->type     = 2;
                $users->code     = rand(1000, 9999);
                $users->content  = "【**科技】正在进行手机号码操作，您的验证码是" . $users->code;
                $users->save();
                return $this->sendCode($bphone, $users->content);
            } else {
                $ret['err']     = 1;
                $ret['msg']     = 'The phone has binded';
                $ret['content'] = $bphone;

                return $ret;
            }
        } else {
            $ret['err']     = 1;
            $ret['msg']     = 'The phone has not regiested';
            $ret['content'] = $bphone;
        }
    }

    public function sendCode($phone, $content)
    {
        require_once base_path('vendor/yunpian-sdk-php/YunpianAutoload.php');
        $smsOperator    = new \SmsOperator();
        $data['mobile'] = $phone;
        $data['text']   = $content;
        $result         = $smsOperator->single_send($data);
        $ret['err']     = 0;
        $ret['msg']     = '短信发送成功';
        $ret['content'] = $data;

        return $ret;
        // $sphone = $phone;
        // $num         = rand(1000, 9999);
        // $mobil       = $request->input('phone');
        // $mobil = $sphone;
        // $data['tpl_id']    = "1";
        // $data['tpl_value'] =
        // urlencode("#code#") . "="
        // . urlencode($num) . "&"
        // . urlencode("#company#") . "="
        // . urlencode("**科技");
        // $result = $smsOperator->tpl_send($data);

        // Log::info("发送的验证信息是：", $result);

    }

    public function check_phone($phone)
    {
        $reg_phone = $phone;
        $user_info = User::all(['mobile']);
        $mobile    = [];
        foreach ($user_info as $ukey => $uvalue) {
            $mobile[] = $uvalue->mobile;
        }

        if (in_array($reg_phone, $mobile)) {
            return true;
        }

        return false;
    }

}
