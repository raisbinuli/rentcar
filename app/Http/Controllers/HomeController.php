<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Cart;
use App\Models\CartItem;
use DB;

class HomeController extends Controller
{
    //
    public function index(){
        $data['allCars'] = Car::select('id','brand')->orderBy('id','desc')->get();
        return view('web.index',$data);
    }

    public function getCar($id){
        $car = new Car();

        if(count($car->getById($id)) > 0){            
            $car            = $car->getById($id);
            $car[0]->disc   = $this->getDisc($car[0]);
            return response()->json($car,200);
        }
        return response()->json(array("message" => "Data not found"),404);
        
    }

    public function getTempCarts(Request $req){

        $dataReturn['carts'] = Car::whereIn('id',$req->input('data'))->get();

        if(count($dataReturn['carts']) > 0){            
            $counter = 0;
            foreach($dataReturn['carts'] as $car){
                $dataReturn['carts'][$counter]->disc = $this->getDisc($car);
                $counter++;
            }

            return response()->json($dataReturn,200);
        }
        return response()->json(array("message" => "Data not found"),404);

    }    

    public function nextStep(){
        return view('web.next');
    }

    public function getAllCarts(Request $req){
        
        $carts      = Car::whereIn('id',$req->input('carts'))->get();
        $dataReturn = $this->getTotalPrice($carts,$req->input('days'));

        return response()->json($dataReturn,200);
    }

    public function createCarts(Request $req){
        $req->validate([
            'name' => 'required',            
            'phone' => 'required|regex:/^[0-9]+$/|max:20',
            'address' => 'required',
            'carts' => 'required',
            'days' => 'required',
            'total_price' => 'required',
        ]);

        DB::beginTransaction();

        try{
            $cart = new Cart;
            
            $cart->name         = $req->input('name');
            $cart->phone        = $req->input('phone');
            $cart->address      = $req->input('address');
            $cart->days         = $req->input('days');
            $cart->total_price  = $req->input('total_price');
            $cart->user_id      = 0;

            $cart->save();

            foreach($req->input('carts') as $item){
                $cart_item      = new CartItem;
                $cart_item->cart_id  = $cart->id;
                $cart_item->car_id   = $item;
                $cart_item->save();
            }

            DB::commit();
        }
        catch(\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $dataReturn["status"]   = "Error";
            $dataReturn["message"]  = 'Error (' . $e->errorInfo[1] . '): ' . $e->errorInfo[2] . '.';
            return response()->json($dataReturn, 422);
        }

        return response()->json(array("status" => "Success"),200);
    }

    private function getDisc($car){
        return $car->disc = $car->built < 2010 ?  '7%' : "";
    }

    public function finished(){
        
        return view('web.finished');
    }

    private function getTotalPrice($objectCarts,$days){
        $counter = 0;

        $totalPrice = 0;

        foreach($objectCarts as $object){
            
            $objectCarts[$counter]->price_disc = $object->built < 2010 ? $object->price - ($object->price  * 0.07) : 0;            

            if($objectCarts[$counter]->price_disc != 0){
                $totalPrice = $totalPrice +  $objectCarts[$counter]->price_disc;
            }
            else
            $totalPrice = $totalPrice + $objectCarts[$counter]->price;
            // $totalPrice = $totalPrice + $objectCarts[$counter]->price_disc != 0 ? $objectCarts[$counter]->price_disc : $objectCarts[$counter]->price;
            $counter++;
        }

        $dataReturn['carts'] = $objectCarts;

        $dataReturn['totalPrice'] = $totalPrice;
        
        if($objectCarts->count() > 2){
            $dataReturn['totalPriceDiscCars'] = $totalPrice * 0.1;
            $dataReturn['totalPriceBeforeDisc'] = $totalPrice;
            
        }

        $dataReturn['totalPrice'] = $dataReturn['totalPrice'] * $days;

        if($days > 3){
            $dataReturn['totalPriceBeforeDisc'] = $totalPrice;
            $dataReturn['totalPriceDiscDays'] = $totalPrice * 0.05;
            $dataReturn['totalPriceBeforeDiscDays'] = $totalPrice  * $days;
            
        }

        if(isset($dataReturn['totalPriceDiscDays'])){
            $dataReturn['totalPrice'] = $dataReturn['totalPrice'] - $dataReturn['totalPriceDiscDays'];
        }

        if(isset($dataReturn['totalPriceDiscCars'])){
            $dataReturn['totalPrice'] = $dataReturn['totalPrice'] - $dataReturn['totalPriceDiscCars'];
        }
        
        $dataReturn['days'] = $days;
        $dataReturn['totalCars'] = $objectCarts->count();

        return $dataReturn;

    }

}
