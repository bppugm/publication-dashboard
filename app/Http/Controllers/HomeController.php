<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
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
    //
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index(Dashboard $dashboard)
  {
    $dashboards = Dashboard::active()->orderBy('order', 'asc')->get();
    $dashboards->load('data');

    return view('welcome', compact('dashboards'));
  }
}
