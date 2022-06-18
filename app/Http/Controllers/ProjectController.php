<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\ProductBid;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('type', 'lelang')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('crowdfunding.funding', $data->id) . '" type="button" name="edit" id="' . $data->id . '" class="edit btn btn-success btn-sm">Add Bid</a>';
                    $button .= '&nbsp;&nbsp;&nbsp;<a data-toggle="confirmation" data-singleton="true" data-popout="true" href="' . route('crowdfunding.delete', $data->id) . '" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"' . "onclick='return'" . '>Delete</a>';
                    return $button;
                })->addColumn('photo', function ($data) {
                    return '<img src="' . Storage::url($data->photo) . '" width="100px" height="100px" />';
                })
                ->addColumn('category', function ($data) {
                    return ProductCategory::find($data->id_category)->name;
                })
                ->addColumn('deadline', function ($data) {
                    return \Carbon\Carbon::createFromTimeStamp(strtotime($data->auction_deadline))->diffForHumans().'<br>'.$data->auction_deadline;
                })
                ->addColumn('lastest_bid', function ($data) {
                    $check_lastest = ProductBid::where('id_product', $data->id)->orderBy('created_at', 'desc')->first();
                    if ($check_lastest == null) {
                        return 'No Bid';
                    }
                    $hasil_rupiah = "Rp " . number_format($check_lastest->amount, 2, ',', '.');
                    return $hasil_rupiah;
                })
                ->rawColumns(['action', 'photo', 'price', 'lastest_bid', 'deadline'])
                ->make(true);
        }

        return view('pages.crowdfunding.crowdfunding');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_view()
    {
        $categories = ProductCategory::all();
        return view('pages.crowdfunding.create', compact('categories'));
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
            'address' => 'required',
            'description' => 'required',
            'category' => 'required',
            'suitable'  => 'required',
            'price'  => 'required',
            'deadline'  => 'required',
            'photo'  => 'required',
        ]);


        if ($request->deadline < Carbon::now()) {
            return redirect()->back()->with('error', 'Deadline must be greater than current date');
        }

        $product = new Product();
        $product->id_store = Store::where('id_user', Auth::user()->id)->first()->id;
        $product->type = "lelang";
        $product->title = $request->title;
        $product->address = $request->address;
        $product->description = $request->description;
        $product->id_category = $request->category;
        $product->suitable = $request->suitable;
        $product->price = $request->price;
        $product->auction_deadline = $request->deadline;
        $product->photo = Storage::disk('public')->put('product', $request->file('photo'));
        $product->save();

        return redirect()->route('crowdfunding')->withSuccess('Auction Post created successfully.');
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

        return redirect()->route('crowdfunding')->withSuccess('Auction Post deleted successfully.');
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
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
