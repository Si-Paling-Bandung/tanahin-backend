<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProjectFunding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ProductBid;

class ProjectFundingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = ProductBid::where('id_product', $id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<a data-toggle="confirmation" data-singleton="true" data-popout="true" href="' . route('crowdfunding.funding.delete', [$data->id_product, $data->id]) . '" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"' . "onclick='return'" . '>Delete</a>';
                    return $button;
                })->addColumn('user', function ($data) {
                    return User::find($data->id_user)->name;
                })
                ->addColumn('price', function ($data) {
                    $hasil_rupiah = "Rp " . number_format($data->amount, 2, ',', '.');
                    return $hasil_rupiah;
                })
                ->rawColumns(['action', 'user', 'price'])
                ->make(true);
        }

        return view('pages.crowdfunding.funding', compact('id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_view(Request $request, $id)
    {
        $data_user = User::all();
        $data_product = Product::find($id);
        $check_lastest = ProductBid::where('id_product', $id)->orderBy('created_at', 'desc')->first();
        $min_bid = 0;
        if (!$check_lastest == null) {
            $min_bid = $check_lastest->amount;
        }
        return view('pages.crowdfunding.funding-create', compact('id', 'data_user', 'data_product', 'min_bid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_process(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required',
            'user' => 'required',
        ]);

        $check_lastest = ProductBid::where('id_product', $id)->orderBy('created_at', 'desc')->first();
        $min_bid = 0;
        if (!$check_lastest == null) {
            $min_bid = $check_lastest->amount;
        }

        $product_bid = new ProductBid();
        $product_bid->id_product = $id;
        $product_bid->id_user = $request->user;
        if ($request->amount <= $min_bid) {
            return redirect()->back()->with('error', 'Bid must be greater than ' . $min_bid);
        }
        $product_bid->amount = $request->amount;
        $product_bid->save();

        return redirect()->route('crowdfunding.funding', compact('id'))->withSuccess('Auction Bid created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($id_product_variant, $id)
    {
        $project_funding = ProductBid::find($id);
        $project_funding->delete();

        return redirect()->route('crowdfunding.funding', $id_product_variant)->withSuccess('Auction Bid deleted successfully.');
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
     * @param  \App\Models\ProjectFunding  $projectFunding
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectFunding $projectFunding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectFunding  $projectFunding
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectFunding $projectFunding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectFunding  $projectFunding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectFunding $projectFunding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectFunding  $projectFunding
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectFunding $projectFunding)
    {
        //
    }
}
