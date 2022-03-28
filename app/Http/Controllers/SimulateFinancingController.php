<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SimulateFinancingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'entry_amount' => 'required|numeric|gt:0',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        
        if($request->entry_amount/100 <= $vehicle->value)
            return response()->json(['entry_amount' => 'Must be less than vehicle value'], 422);

        return response()->json([
            'installments' => [
                '6' => ($vehicle->value - $request->entry_amount/100)/6,
                '12' => ($vehicle->value - $request->entry_amount/100)/12,
                '48' => ($vehicle->value - $request->entry_amount/100)/48,
            ]
        ]);
    }
}
