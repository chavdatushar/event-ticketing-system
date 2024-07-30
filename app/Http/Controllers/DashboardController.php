<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Str;

class DashboardController extends Controller
{

    public function index(Request $request)
    {

        return view('dashboard.index');
    }
}
