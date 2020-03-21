<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;

class CarsController extends Controller
{
    //
    
    public function index(){
        $data['allCars'] = Car::get();

        return view('dashboard.cars.index',$data);
    }

    public function store(Request $req){
        
        $req->validate([
            'brand' => 'required|max:100',
            'built' => 'required|digits:4|integer|min:1900|',
            'price' => 'required|regex:/^[0-9]+$/',
        ]);

        try{
            $car = new Car;
            
            $car->brand = $req->input('brand');
            $car->built = $req->input('built');
            $car->price = $req->input('price');

            $car->save();
        }
        catch(\Illuminate\Database\QueryException $e) {
            
            $dataReturn["status"] = "Error";
            $dataReturn["message"] = 'Error (' . $e->errorInfo[1] . '): ' . $e->errorInfo[2] . '.';
            return response()->json($dataReturn, 422);
        }

        return response()->json(array("status" => "Success"),200);
    }

    public function getCar($id){
        $car = new Car();

        if(count($car->getById($id)) > 0){
            return response()->json($car->getById($id),200);
        }
        return response()->json(array("message" => "Data not found"),404);
        
    }

    public function update(Request $req){
        
        $req->validate([
            'brand' => 'required|max:100',
            'built' => 'required|digits:4|integer|min:1900|',
            'price' => 'required|regex:/^[0-9]+$/',
            'id' => 'required|regex:/^[0-9]+$/',
        ]);

        try{
            Car::where('id',$req->input('id'))
            ->update(
                array(
                    'brand' => $req->input('brand'),
                    'built' => $req->input('built'),
                    'price' => $req->input('price'),
                )
            );
        }
        catch(\Illuminate\Database\QueryException $e) {
            
            $dataReturn["status"] = "Error";
            $dataReturn["message"] = 'Error (' . $e->errorInfo[1] . '): ' . $e->errorInfo[2] . '.';
            return response()->json($dataReturn, 422);
        }

        return response()->json(array("status" => "Success"),200);
    }

    public function destroy($id){
        try{
            Car::where('id', $id)->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            
            $dataReturn["status"] = "Error";
            $dataReturn["message"] = 'Error (' . $e->errorInfo[1] . '): ' . $e->errorInfo[2] . '.';
            return response()->json($dataReturn, 422);
        }

        return response()->json(array("status" => "Success"),200);
    }

       
}
