<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use App\User;
use App\Models\Product;
use App\Models\Thread;
use App\Models\Education;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product = Product::all()->count();
        $user = User::all()->count();
        $thread = Thread::all()->count();

        $widget = [
            'user' => $user,
            'product' => $product,
            'thread' => $thread,
        ];

        return view('home', compact('widget'));
    }
}
