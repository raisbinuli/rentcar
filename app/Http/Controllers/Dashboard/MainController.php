<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class MainController extends Controller
{
    //

    public function index(){
        $data['allDataCart'] = Cart::get();
        return view('dashboard.main',$data);
    }
    
}
