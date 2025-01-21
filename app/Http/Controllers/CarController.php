<?php

namespace App\Http\Controllers;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = User::find(1)
            ->cars()
            ->with(['primaryImage', 'maker', 'model'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('car.index', ['cars' => $cars]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get request data
        $data = $request->validate([
            'maker_id' => 'required',
            'model_id' => 'required',
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => 'required|integer|min:0',
            'vin' => 'required|string|size:17',
            'mileage' => 'required|integer|min:0',
            'car_type_id' => 'required|exists:car_types,id',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'required|string|min:9',
            'description' => 'nullable|string',
            'published_at' => 'nullable|string',
            'features' => 'array',
            'features.*' => 'string',
            'images' => 'array',
            'images.*' => File::image()
                ->max(2048)
        ]);

        dd($data);

        // Get features data
        $featuresData = $data['features'];

        // Get images
        $images = $request->file('images') ?: [];

        // Set user ID
        $data['user_id'] = 1;
        // Create new car
        $car = Car::create($data);

        // Create features
        $car->features()->create($featuresData);

        // Iterate and create images
        foreach ($images as $i => $image) {
            // Save image on file system
            $path = $image->store('public/images');
            // Create record in the database
            $car->images()->create(['image_path' => $path, 'position' => $i + 1]);
        }

        // Redirect to car.index route
        return redirect()->route('car.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        if( ! $car->published_at ) {
            abort(404);
        }
        return view('car.show', ['car' => $car]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {

        dd($car->features);
        return view('car.edit', ['car' => $car]);
    }

    public function search(Request $request)
    {
        $maker = $request->integer('maker_id');
        $model = $request->integer('model_id');
        $carType = $request->integer('car_type_id');
        $fuelType = $request->integer('fuel_type_id');
        $state = $request->integer('state_id');
        $city = $request->integer('city_id');
        $yearFrom = $request->integer('year_from');
        $yearTo = $request->integer('year_to');
        $priceFrom = $request->integer('price_from');
        $priceTo = $request->integer('price_to');
        $mileage = $request->integer('mileage');
        $sort = $request->input('sort', '-published_at');

        $query = Car::where('published_at', '<', now())
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model']);

        if ($maker) {
            $query->where('maker_id', $maker);
        }
        if ($model) {
            $query->where('model_id', $model);
        }
        if ($state) {
            $query->join('cities', 'cities.id', '=', 'cars.city_id')
                ->where('cities.state_id', $state);
        }
        if ($city) {
            $query->where('city_id', $city);
        }
        if ($carType) {
            $query->where('car_type_id', $carType);
        }
        if ($fuelType) {
            $query->where('fuel_type_id', $fuelType);
        }
        if ($yearFrom) {
            $query->where('year', '>=', $yearFrom);
        }
        if ($yearTo) {
            $query->where('year', '<=', $yearTo);
        }
        if ($priceFrom) {
            $query->where('price', '>=', $priceFrom);
        }
        if ($priceTo) {
            $query->where('price', '<=', $priceTo);
        }
        if ($mileage) {
            $query->where('mileage', '<=', $mileage);
        }

        if (str_starts_with($sort, '-')) {
            $sortBy = substr($sort, 1);
            $query->orderBy($sortBy, 'desc');
        } else {
            $query->orderBy($sort);
        }

        $cars = $query->paginate(15)->withQueryString();

        return view('car.search', ['cars' => $cars]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }

    public function watchlist()
    {
        // TODO we come back to this
        $cars = User::find(4)
            ->favouriteCars()
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->paginate(15);

        return view('car.watchlist', ['cars' => $cars]);
    }
}
