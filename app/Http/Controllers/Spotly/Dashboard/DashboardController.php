<?php

namespace App\Http\Controllers\Spotly\Dashboard;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return $this->inertiaRender('dashboard/Dashboard');
    }
}
