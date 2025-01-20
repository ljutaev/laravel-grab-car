<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Maker;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Model;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::with(['city', 'carType', 'fuelType', 'maker', 'model', 'primaryImage'])
            ->where('published_at', '<', now())
            ->orderBy('published_at', 'desc')
            ->limit(30)
            ->get();
        return view('home.index', compact('cars'));
    }
}
