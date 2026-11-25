<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Club;

class HomeController extends Controller
{
    public function __invoke()
    {
        // with() n'est pas nécessaire ici (pas de relation affichée) ; tri simple.
        $clubs = Club::active()->orderBy('name')->get();

        return view('public.home', compact('clubs'));
    }
}
