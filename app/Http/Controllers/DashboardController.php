<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Send the authenticated user to the dashboard that matches their role.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->role->dashboardRoute());
    }
}
