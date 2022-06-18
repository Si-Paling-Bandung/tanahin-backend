<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('product.review', $data->id) . '" type="button" name="edit" id="' . $data->id . '" class="edit btn btn-warning btn-sm">Add Review</a>';
                    $button .= '&nbsp;&nbsp;&nbsp;<a data-toggle="confirmation" data-singleton="true" data-popout="true" href="' . route('product.delete', $data->id) . '" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"' . "onclick='return'" . '>Delete</a>';
                    return $button;
                })
                ->addColumn('price', function ($data) {
                    $hasil_rupiah = "Rp " . number_format($data->price, 2, ',', '.');
                    return $hasil_rupiah;
                })
                ->addColumn('photo', function ($data) {
                    return '<img src="' . Storage::url($data->photo) . '" width="100px" height="100px" />';
                })
                ->addColumn('category', function ($data) {
                    return ProductCategory::find($data->id_category)->name;
                })
                ->rawColumns(['action', 'photo', 'price', 'category'])
                ->make(true);
        }

        return view('pages.product.product');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_view()
    {
        $categories = ProductCategory::all();
        return view('pages.product.create', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_process(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'address' => 'required',
            'description' => 'required',
            'category' => 'required',
            'suitable'  => 'required',
            'price'  => 'required',
            'discounted_price'  => 'required',
            'photo'  => 'required',
        ]);

        $product = new Product();
        $product->id_store = Store::where('id_user', Auth::user()->id)->first()->id;
        $product->type = $request->type;
        $product->title = $request->title;
        $product->address = $request->address;
        $product->description = $request->description;
        $product->id_category = $request->category;
        $product->suitable = $request->suitable;
        $product->price = $request->price;
        $product->discounted_price = $request->discounted_price;
        $product->photo = Storage::disk('public')->put('product', $request->file('photo'));
        $product->save();

        return redirect()->route('product')->withSuccess('Product created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('product')->withSuccess('Product deleted successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
