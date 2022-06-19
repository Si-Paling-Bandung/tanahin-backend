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
            $data = Product::where('type', 'jual')->get();
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
            'title' => 'required',
            'area' => 'required',
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
        $product->type = 'jual';
        $product->title = $request->title;
        $product->area = $request->area;
        $product->address = $request->address;
        $product->description = $request->description;
        $product->id_category = $request->category;
        $product->suitable = $request->suitable;
        $product->price = $request->price;
        $product->discounted_price = $request->discounted_price;
        $product->photo = Storage::disk('public')->put('product', $request->file('photo'));

        $product->price_meter = (int)$request->price / (int)$request->area;

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

    // ===================================================================================================
    // ====================================== installment ===============================================
    // ===================================================================================================

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_installment(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('type', 'cicilan')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<a data-toggle="confirmation" data-singleton="true" data-popout="true" href="' . route('installment.delete', $data->id) . '" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"' . "onclick='return'" . '>Delete</a>';
                    return $button;
                })
                ->addColumn('price', function ($data) {
                    $hasil_rupiah = "Rp " . number_format($data->price, 2, ',', '.');
                    return $hasil_rupiah;
                })
                ->addColumn('monthly_pay', function ($data) {
                    $monthly_pay = "Rp " . number_format($data->installment_pay, 2, ',', '.');
                    return $monthly_pay;
                })
                ->addColumn('front_payment', function ($data) {
                    return $data->dp . "%";
                })
                ->addColumn('tenor_month', function ($data) {
                    return $data->tenor . " Month - " . ($data->tenor / 12) . " Year";
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

        return view('pages.installment.installment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_view_installment()
    {
        $categories = ProductCategory::all();
        return view('pages.installment.create', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_process_installment(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'area' => 'required',
            'address' => 'required',
            'description' => 'required',
            'category' => 'required',
            'suitable'  => 'required',
            'price'  => 'required',
            'dp'  => 'required',
            'tenor'  => 'required',
            'photo'  => 'required',
        ]);

        $product = new Product();
        $product->id_store = Store::where('id_user', Auth::user()->id)->first()->id;
        $product->type = 'cicilan';
        $product->title = $request->title;
        $product->area = $request->area;
        $product->address = $request->address;
        $product->description = $request->description;
        $product->id_category = $request->category;
        $product->suitable = $request->suitable;
        $product->price = $request->price;
        $product->dp = $request->dp;
        $product->tenor = $request->tenor;
        $product->photo = Storage::disk('public')->put('product', $request->file('photo'));

        $product->price_meter = (int)$request->price / (int)$request->area;

        $price_mines_dp = (int)$request->price - ((int)$request->price * ((int)$request->dp / 100));
        $price_with_fee = $price_mines_dp + (($price_mines_dp * 0.01));
        $bank_interest = 0.05;
        $product->installment_pay = ($price_with_fee + ($price_with_fee * $bank_interest)) / (int)$request->tenor;
        $product->save();

        $product->save();

        return redirect()->route('installment')->withSuccess('Installment created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete_installment($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('installment')->withSuccess('Installment deleted successfully.');
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
