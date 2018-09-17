<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class BaseController extends Controller
{
    /**
     * @return User
     */
    protected function me()
    {
        return Auth::user();
    }

    /**
     * Setup the Layout used by the controller.
     * 
     * @return Void
     */
    protected function setupLayout(Chat $chat)
    {
        if(! is_null($this->layout)){
            $this->layout = View::make($this->layout);
        }
    }
}
