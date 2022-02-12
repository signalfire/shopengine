<?php

namespace Signalfire\Shopengine\Http\Controllers\Web;

class HomeController extends Controller
{
    public function __invoke(){
        return view('shopengine::web.home');
    }
}