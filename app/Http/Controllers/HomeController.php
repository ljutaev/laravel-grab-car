<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $car = Car::find(1);

//        $car->favouredUsers()->attach(1);

//        dd($car->favouredUsers()->get() );

        $user = User::find(1);

        dd( $user->favouriteCars()->get() );

        return view('home.index');

    }
}
