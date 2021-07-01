<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::leftJoin('categories','categories.id', '=', 'products.category_id')
            ->get(['products.*']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'category_id' => 'required',
            'expired_date' => 'required',
            'image' => 'required',
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $product = new Product();

            $upload_path = public_path('upload');
            $file_name = $request->file->getClientOriginalName();
            $generated_new_name = time() . '.' . $request->file->getClientOriginalExtension();
//            $request->file->move($upload_path, $generated_new_name);
            $request->file->storeAs('public/uploads',$generated_new_name);
            $product->image = $generated_new_name;
            $product->path = $upload_path;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->qty = $request->qty;
            $product->category_id = $request->category_id;
            $product->expired_date = $request->expired_date;
            $product->created_at = now();
            $product->save();

            return response(['success'=>'Data Inserted successfully.'],200);




//        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::leftJoin('categories','categories.id', '=', 'products.category_id')
            ->where('products.id',$id)
            ->get(['products.*','categories.name']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'category_id' => 'required',
            'expired_date' => 'required',
            'image' => 'required',
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $product = [];

        if($request->file() && !empty($id)) {
            $upload_path = public_path('upload');
            $file_name = $request->file->getClientOriginalName();
            $generated_new_name = time() . '.' . $request->file->getClientOriginalExtension();
//            $request->file->move($upload_path, $generated_new_name);
            $request->file->storeAs('public/uploads',$generated_new_name);
            $product['image'] = $generated_new_name;
            $product['path'] = $file_name;
            $product['name'] = $request->name;
            $product['price'] = $request->price;
            $product['qty'] = $request->qty;
            $product['category_id'] = $request->category_id;
            $product['expired_date'] = $request->expired_date;
            $product->timestamps = true;

            Product::update(['id' => $id], $product);

            return response(['success'=>'Data uploaded successfully.'],200);
        } else if(!$request->file() && !empty($id)) {
            $product['name'] = $request->name;
            $product['price'] = $request->price;
            $product['qty'] = $request->qty;
            $product['category_id'] = $request->category_id;
            $product['expired_date'] = $request->expired_date;
            $product['updated_at'] = now();

            Product::update(['id' => $id], $product);

            return response(['success'=>'Data uploaded successfully.'],200);
        }

        return response(['success'=>'Data uploaded failed .'],200);
//        $product = Product::find($id);
//        $product->update($request->all());
//        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::leftJoin('categories','categories.id', '=', 'products.category_id')
            ->where('products.name', 'like', '%'.$name.'%')
            ->orWhere('categories.name', 'like', '%'.$name.'%')
            ->get(['products.*','categories.name']);
//        return Product::where('name', 'like', '%'.$name.'%')->get();
    }

    public function getProductsByCategory(Request $request){
        $categoriesId = [];
        foreach ($request->all() as $value){
            $categoriesId[] = $value['id'];
        }
//        dd(Product::whereIn('category_id', $categoriesId)->get());
        return Product::whereIn('category_id', $categoriesId)->get(['products.*','products.qty as stock']);
    }

    public function imageUploadPost(Request $request)
    {

        $request->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);



        $imageName = time().'.'.$request->image->extension();



        $request->image->move(public_path('images'), $imageName);



        /* Store $imageName name in DATABASE from HERE */

        return $imageName;

//        return back()
//
//            ->with('success','You have successfully upload image.')
//
//            ->with('image',$imageName);

    }

    public function upload(Request $request){

        dd($request->all());

        $upload_path = public_path('upload');
        $file_name = $request->file->getClientOriginalName();
        $generated_new_name = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move($upload_path, $generated_new_name);

        return response()->json(['success' => 'You have successfully uploaded "' . $file_name . '"']);
    }
}
