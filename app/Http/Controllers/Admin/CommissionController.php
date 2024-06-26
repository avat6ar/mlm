<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{

  public function index()
  {
    $commission = Commission::first();
    $pageTitle = 'Commission Settings';

    return view('admin.commission.index', compact('commission', 'pageTitle'));
  }
}
