<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CategoriesModel;
use App\Models\ProductQtyModel;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = ProductQtyModel::select('products.id','products.name','products.description','products.price','products.unit', 'categories.name as category', 'categories.id as category_id', DB::raw('SUM(product_qty.qty) AS qty'))
            ->join('products', 'products.id', '=', 'product_qty.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->groupBy('products.id')
            ->groupBy('category')
            ->groupBy('products.name')
            ->groupBy('products.description')
            ->groupBy('products.price')
            ->groupBy('products.unit')
            ->groupBy('categories.id')
            ->orderBy('category')
            ->orderBy('products.name')
            ->whereNull('deleted_at')
            ->get();
        foreach ($data as $item){
            $item['product_qty'] = ProductQtyModel::where('product_id', $item['id'])->orderBy('warehouse')->get();
        }
        return view('admin.products.index', ['data'=>$data]);
    }

    public function getDetail(Request $request)
    {
        $request_all = $request->all();
        $data = ProductQtyModel::select('products.id','products.name','products.description','products.price','products.unit', 'categories.name as category', 'categories.id as category_id', DB::raw('SUM(product_qty.qty) AS qty'))
            ->join('products', 'products.id', '=', 'product_qty.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->groupBy('products.id')
            ->groupBy('category')
            ->groupBy('products.name')
            ->groupBy('products.description')
            ->groupBy('products.price')
            ->groupBy('products.unit')
            ->groupBy('categories.id')
            ->orderBy('category')
            ->orderBy('products.name')
            ->whereNull('deleted_at')
            ->where('products.id', $request_all['id'])
            ->first();

        $data['product_qty'] = ProductQtyModel::where('product_id', $data['id'])->orderBy('warehouse')->get();
        return Helpers::dataSuccess('Success', $data);
    }

    public function save(Request $request)
    {
        try {
            $request_data = $request->all();
            $validator = Validator::make($request_data, [
                'name' => 'required',
                'unit' => 'required',
                'category_id' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $model = $request_data['id'] ? ProductsModel::query()->findOrFail($request_data['id']) : new ProductsModel();
            $mes = $request_data['id'] ? 'Updated success!' : 'Created success!';
            $model->fill($request_data);

            if ($model->save()){
                if (!$request_data['id']){
                    $qty = new ProductQtyModel();
                    $qty['product_id'] = $model['id'];
                    $qty['warehouse'] = 1;
                    $qty['qty'] = 0;
                    $qty->save();
                }

                return redirect()->route('products.index')->with(['success' =>$mes]);
            }
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        } catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['general' =>'sorry an unexpected error occurred. please try again later']);
        }
    }

    public function delete(Request  $request)
    {
        try {
            $request_data = $request->all();
            $model = ProductsModel::query()->findOrFail($request_data['id']);
            if ($model->delete()){
                return Helpers::dataSuccess('Success');
            }
            return Helpers::dataError('sorry an unexpected error occurred. please try again later');
        } catch (\Exception $e){
            return Helpers::dataError('sorry an unexpected error occurred. please try again later');
        }
    }

    public function productsUpdateQty(Request $request)
    {
        try {
            $request_data = $request->all();
            $model = ProductsModel::query()->findOrFail($request_data['id']);
            if ($model){
                $w = 4;
                for($i = 1; $i <= $w; $i++){
                    $check = ProductQtyModel::where('product_id', $request_data['id'])->where('warehouse', $i)->first();
                    if ($check){
                        $check['qty'] = $request_data['qty'.$i];
                        $check->save();
                    } else{
                        $new = new ProductQtyModel();
                        $new['product_id'] = $request_data['id'];
                        $new['warehouse'] = $i;
                        $new['qty'] = $request_data['qty'.$i];
                        $new->save();
                    }
                }
                $data = ProductQtyModel::where('product_id', $request_data['id'])->orderBy('warehouse')->get();
                return Helpers::dataSuccess('Success', $data);
            }
            return Helpers::dataError('sorry an unexpected error occurred. please try again later');
        } catch (\Exception $e){
            return Helpers::dataError('sorry an unexpected error occurred. please try again later');
        }
    }

    public function ajaxListProduct(Request  $request)
    {
        $list = DB::table("products")
            ->where("category_id", $request->category)
            ->get();
        $data = ProductQtyModel::select('products.id','products.name','products.description','products.price','products.unit', 'categories.name as category', 'categories.id as category_id', DB::raw('SUM(product_qty.qty) AS qty'))
            ->join('products', 'products.id', '=', 'product_qty.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where("category_id", $request->category)
            ->groupBy('products.id')
            ->groupBy('category')
            ->groupBy('products.name')
            ->groupBy('products.description')
            ->groupBy('products.price')
            ->groupBy('products.unit')
            ->groupBy('categories.id')
            ->get();
        return response()->json($data);
    }
}
