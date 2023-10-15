<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CategoriesModel;
use App\Models\ImageProductOrdersModel;
use App\Models\OrderProducts;
use App\Models\OrderProductsModel;
use App\Models\OrdersModel;
use App\Models\ProductsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class OrdersController extends Controller
{
    //
    public function index()
    {
        $orders = OrdersModel::orderBy('id', 'desc')->get();
        return view('admin.orders.index', ['data'=>$orders]);
    }

    public function formOrder($id = null)
    {
//        try {
            $categories = CategoriesModel::orderBy('name', 'asc')->get();
            if ($id){
                $products = [];
                Session::put('productInOrder', $products);
                $model = $id ? OrdersModel::query()->findOrFail($id) : new OrdersModel();
                $products = OrderProductsModel::select('order_products.id as order_products_id','products.category_id as category','products.*', 'products.name as product_name', 'order_products.quantity', 'order_products.price as price_product', 'order_products.product_id')
                    ->where('order_id', $id)->join('products', 'products.id', '=', 'product_id')
                    ->orderBy('name', 'asc')->get();

//                dd($products);
                return view('admin.orders.form_edit', ['model'=>$model, 'categories'=>$categories, 'products'=>$products]);
            } else {
                $model = new OrdersModel();
                $products = [];
                Session::put('productInOrder', $products);
                return view('admin.orders.form', ['model'=>$model, 'categories'=>$categories]);
            }
//        } catch (\Exception $e){
//            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
//        }
    }

    public function saveFormOrder(Request $request)
    {
        try {
            $request_data = $request->all();
            $model = $request_data['id'] ? OrdersModel::query()->findOrFail($request_data['id']) : new OrdersModel();
            $validator = Validator::make($request_data, [
                'recipient_name' => 'required',
                'recipient_phone' => 'required',
                'recipient_address' => 'required',
                'total_price' => 'required',
            ]);
            $message = empty($request_data['id']) ? 'Create order success' : 'Update order success';
            if (empty($request_data['id'])){
                $date = Carbon::today();
                $max = OrdersModel::select(DB::raw('max(cast(substring(order_code, 9) as SIGNED)) AS code_order'))
                    ->whereDate('created_at', $date)
                    ->first();
                $code_order = $max->code_order ? $max->code_order : 0;
                $ma = $code_order ? $code_order : 0;
                if (is_int($ma)){
                    $num = (int)$ma + 1;
                    $ma = '00' . (string) $num;
                    if (strlen($ma) <= 5){
                        $ma = '#'.$date->isoFormat('MMDDYY').'-'.substr($ma, strlen($ma) - 3, 3);
                    } else{
                        $ma = '#'.$date->isoFormat('MMDDYY').'-'.substr($ma, 2);
                    }
                }
                $request_data['order_code'] = $ma;
            }
            if ($validator->fails()){
                $products = [];
                Session::put('productInOrder', $products);
                return redirect()->back()->withInput()->withErrors($validator);
            }
            DB::beginTransaction();
            $model->fill($request_data);
            if ($model->save()){
//                if ($model['order_type'] == 1){
                $list_product = Session::get('productInOrder');
                if ($list_product){
                    foreach ($list_product as $key => $value)
                    {
                        $value['order_id'] = $model['id'];
                        $value['price'] = $value['price_product'];
                        $product_order = new OrderProductsModel();
                        $product_order->fill($value);
                        $product_order->save();

                    }
                    $products = [];
                    Session::put('productInOrder', $products);
                }
//                }
                DB::commit();
                return redirect()->route('orders.index')->with(['success' =>$message]);
            }
            $products = [];
            Session::put('productInOrder', $products);
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        } catch (\Exception $e){
            $products = [];
            Session::put('productInOrder', $products);
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        }
    }

    public function addProductToOrder(Request $request)
    {
        try {
            $request_data = $request->all();
            $rules = [
                'product_id' => 'required',
                'quantity' => 'required',
                'price_product' => 'required',
                'into_money' => 'required',
            ];
            $validator = Validator::make($request_data, $rules);
            if($validator->fails()) {
                return response()->json(['success'=> false, 'error'=> $validator->messages()], 401);
            }
//            dd($request_data);
            if ($request_data['id_order_product']){

                $request_data['order_id'] = $request_data['id'];
                $request_data['price'] = $request_data['price_product'];
                $product_order = OrderProductsModel::query()->findOrFail($request_data['id_order_product']);
                if ($product_order){
                    $product_order->fill($request_data);
                    $product_order->save();
                }

                $list_product = OrderProductsModel::select('order_products.id as order_products_id','products.category_id as category','products.*', 'products.name as product_name', 'order_products.quantity', 'order_products.price as price_product', 'order_products.product_id')
                    ->where('order_id', $request_data['id'])->join('products', 'products.id', '=', 'product_id')
                    ->orderBy('name', 'asc')->get();
                $total_product_price = 0;
                foreach ($list_product as $p){
                    $p['into_money'] = $p['price_product']*$p['quantity'];
                    $total_product_price = $total_product_price + $p['into_money'];
                }
                $order = OrdersModel::query()->findOrFail($request_data['id']);
                $order['total_price'] = $total_product_price;
                $order->save();
                return Helpers::dataSuccess('Success', $list_product);
            } else{
                if ($request_data['id']){
                    $check = OrderProductsModel::where('order_id', $request_data['id'])->where('product_id', $request_data['product_id'])->first();
                    if ($check){
                        $check['quantity'] = $check['quantity'] + $request_data['quantity'];
                        $check->save();
                    } else{
                        $product_order = new OrderProductsModel();
                        $product_order['order_id']= $request_data['id'];
                        $product_order['product_id']= $request_data['product_id'];
                        $product_order['price']= $request_data['price_product'];
                        $product_order['quantity']= $request_data['quantity'];
                        $product_order->save();
                    }

                    $list_product = OrderProductsModel::select('order_products.id as order_products_id','products.category_id as category','products.*', 'products.name as product_name', 'order_products.quantity', 'order_products.price as price_product', 'order_products.product_id')
                        ->where('order_id', $request_data['id'])->join('products', 'products.id', '=', 'product_id')
                        ->orderBy('name', 'asc')->get();
                    $total_product_price = 0;
                    foreach ($list_product as $p){
                        $p['into_money'] = $p['price_product']*$p['quantity'];
                        $total_product_price = $total_product_price + $p['into_money'];
                    }
                    $order = OrdersModel::query()->findOrFail($request_data['id']);
                    $order['total_price'] = $total_product_price;
                    $order->save();
                    return Helpers::dataSuccess('Success', $list_product);
                } else{
                    $product = ProductsModel::query()->findOrFail($request_data['product_id']);
                    $request_data['product_name'] = $product['name'];
                    $request_data['unit'] = $product['unit'];
                    $products = [];
                    if(Session::get('productInOrder')){
                        $products = Session::get('productInOrder');
                    }

                    if (!empty($request->id_temp)){
                        foreach ($products as $key => $value)
                        {
                            if ($value['id_temp'] == $request_data['id_temp'])
                            {
                                unset($products[$key]);
                            }
                        }
                    } else {
                        $id_temp = rand(1,99999999999);
                        $request_data['id_temp'] = $id_temp;
                    }
                    $productAdd = $request_data;
                    array_push($products, $productAdd);
                    Session::put('productInOrder', $products);
                    $list_product = Session::get('productInOrder');
                    return Helpers::dataSuccess('Success', $list_product);
                }

            }

        } catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        }
    }

    public function removeProductOrder(Request $request)
    {
        try {
            $request_data = $request->all();
            if ($request_data['order_id']){

                $check = OrderProductsModel::query()->findOrFail($request_data['id']);
                $check->delete();
                $list_product = OrderProductsModel::select('order_products.id as order_products_id','products.category_id as category','products.*', 'products.name as product_name', 'order_products.quantity', 'order_products.price as price_product', 'order_products.product_id')
                    ->where('order_id', $request_data['order_id'])->join('products', 'products.id', '=', 'product_id')
                    ->orderBy('name', 'asc')->get();
                $total_product_price = 0;
                foreach ($list_product as $p){
                    $p['into_money'] = $p['price_product']*$p['quantity'];
                    $total_product_price = $total_product_price + $p['into_money'];
                }
                $order = OrdersModel::query()->findOrFail($request_data['order_id']);
                $order['total_price'] = $total_product_price;
                $order->save();
                return Helpers::dataSuccess('Success', $list_product);
            }else{
                if(Session::get('productInOrder')){
                    $cart = Session::get('productInOrder');
                }else{
                    $cart = collect();
                }
                foreach ($cart as $key => $value)
                {
                    if ($value['id_temp'] == $request_data['id'])
                    {
                        unset($cart[$key]);
                    }
                }
                Session::put('productInOrder', $cart);
                $list_product = Session::get('productInOrder');
                return Helpers::dataSuccess('Success', $list_product);
            }

        } catch (\Exception $e){
            return Helpers::dataError('Sorry an unexpected error occurred. please try again later');
        }
    }

    public function addProductToEditOrder(Request $request)
    {
        try {
            $request_data = $request->all();
            $rules = [
                'product_name' => 'required',
                'product_type' => 'required',
                'quantity' => 'required',
                'extra_charges' => 'required',
            ];
            $validator = Validator::make($request_data, $rules);
            if($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $message = empty($request_data['id']) ? 'Add product success' : 'Update product success';
            $model = empty($request_data['id']) ? new OrderProductsModel() : OrderProductsModel::query()->find($request_data['id']);
            $model->fill($request_data);
            if ($model->save()){
                $total_product_price = OrderProductsModel::where('order_id', $request_data['order_id'])->sum('extra_charges');
                $order = OrdersModel::query()->findOrFail($request_data['order_id']);
                $order['total_price'] = (float)$order['custom_fees'] + (float)$order['provincial_transfer_fee'] + (float)$total_product_price;
                $order->save();
                return redirect()->back()->withInput()->with(['success' =>$message]);
            }
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        } catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        }
    }

    public function printLabel(Request $request)
    {
        $request_data = $request->all();
        $model = OrdersModel::query()->findOrFail($request_data['id']);
        $products = OrderProductsModel::select('order_products.id as order_products_id','products.category_id as category','products.*', 'products.name as product_name', 'order_products.quantity', 'order_products.price as price_product', 'order_products.product_id')
            ->where('order_id', $request_data['id'])->join('products', 'products.id', '=', 'product_id')
            ->orderBy('name', 'asc')->get();
        $order_status = '';
        foreach(\App\Models\OrdersModel::STATUS_ALL as $key => $status){
            if ($model->order_status ==  $status['key']){
                $order_status = $status['status_name'];
            }
        }


        $data = [];
        $data['recipient_name'] = $model['recipient_name'];
        $data['recipient_phone'] = $model['recipient_phone'];
        $data['recipient_address'] = $model['recipient_address'];
        $data['order_status'] = $order_status;
        $data['total_price'] = $model['total_price'];
        $data['order_code'] = $model['order_code'];
//        $data['recipient_name'] = $model['recipient_name'];
        $data['products'] = $products;
        $filename = 'label.pdf';
        LaravelMpdf::loadView('admin.label', $data, [], [
            'title' => 'label',
            'margin_top' => 5,
            'format' => 'A4',
            'orientation' => 'L',
        ])->save($filename);

        return Helpers::dataSuccess('Success', asset($filename));
    }
}
