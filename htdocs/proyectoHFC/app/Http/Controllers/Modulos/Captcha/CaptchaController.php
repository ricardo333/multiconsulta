<?php

namespace App\Http\Controllers\Modulos\Captcha;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CaptchaController extends Controller
{
    public function refresh(){
        if(request()->ajax()){
            return captcha_img('match');
        }
        return abort(404);
    }
}
