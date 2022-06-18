<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Certificate;
use App\Models\Config;
use App\Models\Favorites;
use App\Models\Feedback;
use App\Models\GradeQuiz;
use App\Models\Histories;
use App\Models\Lessons;
use App\Models\LOs;
use App\Models\Topics;
use App\Models\TrackingLessons;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\UserCertificate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\Rule;
use App\Models\Log;
use App\Models\Quizzes;
use App\Models\Grade;
use App\Traits\Check;
use App\Models\Rating;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


use App\Models\Education;
use App\Models\LastestTransaction;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Thread;
use App\Models\Project;
use App\Models\ProjectFunding;

class ApiController extends Controller
{
    public function home(Request $request)
    {
        $auction = Product::where('type', '=', 'lelang')->orderBy('created_at', 'desc')->offset(0)->limit(3)->get();
        $rekomendasi = Product::all();

        foreach ($auction as $data) {
            $product_variant = ProductVariant::where('id_product', $data->id)->get();
            $data->{"product_variant"} = $product_variant;

            $rating = 0;
            if (!ProductReview::where('id_product', $data->id)->get()->count() == 0) {
                $rating = ProductReview::where('id_product', $data->id)->get()->sum('rating') / ProductReview::where('id_product', $data->id)->get()->count();
            }

            $data->{"rating"} = round($rating, 1);
        }


        foreach ($rekomendasi as $data) {
            $product_variant = ProductVariant::where('id_product', $data->id)->get();
            $data->{"product_variant"} = $product_variant;

            $rating = 0;
            if (!ProductReview::where('id_product', $data->id)->get()->count() == 0) {
                $rating = ProductReview::where('id_product', $data->id)->get()->sum('rating') / ProductReview::where('id_product', $data->id)->get()->count();
            }

            $data->{"rating"} = round($rating, 1);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'auction' => $auction,
                'rekomendasi' => $rekomendasi
            ]
        ], 200);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $data_all = Product::where('title', 'like', '%' . $request->search_text . '%')->get();

        foreach ($data_all as $data) {
            $product_variant = ProductVariant::where('id_product', $data->id)->get();
            $data->{"product_variant"} = $product_variant;

            $rating = 0;
            if (!ProductReview::where('id_product', $data->id)->get()->count() == 0) {
                $rating = ProductReview::where('id_product', $data->id)->get()->sum('rating') / ProductReview::where('id_product', $data->id)->get()->count();
            }

            $data->{"rating"} = round($rating, 1);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data_all
        ], 200);
    }

    public function update_tb_bb_color(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'berat_badan' => 'required',
            'tinggi_badan' => 'required',
            'warna_kulit' => 'required|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $user = $request->user();
        $user->height = $request->tinggi_badan;
        $user->weight = $request->berat_badan;
        $user->skin_color = $request->warna_kulit;
        $user->save();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function detail_tips(Request $request, $id)
    {
        $data = Education::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function detail_berita(Request $request, $id)
    {
        $data = Thread::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function detail_funding(Request $request, $id)
    {
        $data = Project::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data not found',
            ], 404);
        }

        $total_funding = ProjectFunding::where('id_project', $id)->sum('amount');

        if ($total_funding > $data->target) {
            $total_funding = $data->target;
        }

        if (!$total_funding == 0) {
            $data->{"total_goal"} = round(($total_funding / $data->target) * 100, 2);
        } else {
            $data->{"total_goal"} = 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function detail_produk(Request $request, $id)
    {
        $data = Product::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data not found',
            ], 404);
        }

        $product_variant = ProductVariant::where('id_product', $id)->get();
        $data->{"product_variant"} = $product_variant;

        $rating = 0;
        if (!ProductReview::where('id_product', $id)->get()->count() == 0) {
            $rating = ProductReview::where('id_product', $id)->get()->sum('rating') / ProductReview::where('id_product', $id)->get()->count();
        }

        $data->{"rating"} = round($rating, 1);


        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function forum(Request $request)
    {
        $post = Thread::all();
        $news = Thread::all();
        $tips = Education::all();

        return response()->json([
            'status' => 'success',
            'data' => [
                'post' => $post,
                'news' => $news,
                'tips' => $tips,
            ]
        ], 200);
    }

    public function funding(Request $request)
    {
        $campaign_baru = Project::orderBy('created_at', 'desc')->offset(0)->limit(3)->get();
        foreach ($campaign_baru as $data) {
            $total_funding = ProjectFunding::where('id_project', $data->id)->sum('amount');
            if ($total_funding > $data->target) {
                $total_funding = $data->target;
            }
            if (!$total_funding == 0) {
                $data->{"total_goal"} = round(($total_funding / $data->target) * 100, 2);
            } else {
                $data->{"total_goal"} = 0;
            }
        }

        $trending_campaign  = Project::all();
        foreach ($trending_campaign  as $data) {
            $total_funding = ProjectFunding::where('id_project', $data->id)->sum('amount');
            if ($total_funding > $data->target) {
                $total_funding = $data->target;
            }
            if (!$total_funding == 0) {
                $data->{"total_goal"} = round(($total_funding / $data->target) * 100, 2);
            } else {
                $data->{"total_goal"} = 0;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'trending_campaign ' => $trending_campaign,
                'campaign_baru' => $campaign_baru,
            ]
        ], 200);
    }

    // ================================================================================================

    public function brick_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $response = Http::withToken(
            env('BIRCK_PUBLIC_ACCESS_TOKEN')
        )->post(
            env('BRICK_URL') . '/v1/auth',
            [
                'institution_id' => 11,
                'username' => $request->phone_number,
            ]
        );

        // if data exist return data if not return false
        if (!$response->successful()) {
            return response()->json([
                'message' => 'Please Contact Admin',
                'brick_message' => $response->json(),
            ], 401);
        }

        // return response
        return $response->json();
    }

    public function brick_confirmation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'uniqueId' => 'required',
            'sessionId' => 'required',
            'otpToken' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], 400);
        }

        $response = Http::withToken(
            env('BIRCK_PUBLIC_ACCESS_TOKEN')
        )->post(
            env('BRICK_URL') . '/v1/auth/gopay',
            [
                'username' => $request->username,
                'uniqueId' => $request->uniqueId,
                'sessionId' => $request->sessionId,
                'otpToken' => $request->otpToken,
                'otp' => $request->otp,
            ]
        );

        // if data exist return data if not return false
        if (!$response->successful()) {
            return response()->json([
                'message' => 'Wrong Otp! Please Try Again',
                'brick_message' => $response->json(),
            ], 401);
        }

        // save public access token to database
        $user = User::find($request->user()->id);
        $user->brick_user_access_token = $response['data'];
        $user->save();

        $response = Http::withToken(
            $user->brick_user_access_token
        )->get(
            env('BRICK_URL') . '/v1/account/list'
        );

        $account_id = $response['data'][0]['accountId'];
        $user->brick_account_id = $account_id;
        $user->save();

        $response = Http::withToken(
            $user->brick_user_access_token
        )->get(
            env('BRICK_URL') . '/v1/transaction/list',
            [
                'from' => '2022-04-01',
                'to' => '2022-06-30',
            ]
        );

        $lastest_transaction = LastestTransaction::where('id_user', $user->id)->first();
        if (!$lastest_transaction) {
            $lastest_transaction = new LastestTransaction;
            $lastest_transaction->id_user = $user->id;
            $lastest_transaction->transaction = $response;
            $lastest_transaction->save();
        } elseif ($lastest_transaction) {
            $lastest_transaction->transaction = $response;
            $lastest_transaction->save();
        }

        // processing data
        $collection = collect(json_decode($response, true));
        $data = $collection['data'];
        $income = 0;
        $expense = 0;
        foreach ($data as $dt) {
            $object = json_decode(json_encode($dt), FALSE);
            if ($object->direction == 'in') {
                $income += $object->amount;
            } else {
                $expense += $object->amount;
            }
        }
        $income *= 2;

        $user->profiling = $income - $expense;
        $user->save();

        // return success
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Updated, Please Contact Admin If You Have Any Problem',
            'profiling' => $user->profiling,
        ], 200);
    }

    public function brick_update(Request $request)
    {
        $user = User::find($request->user()->id);

        $response = Http::withToken(
            $user->brick_user_access_token
        )->get(
            env('BRICK_URL') . '/v1/account/list'
        );

        $account_id = $response['data'][0]['accountId'];
        $user->brick_account_id = $account_id;
        $user->save();

        $response = Http::withToken(
            $user->brick_user_access_token
        )->get(
            env('BRICK_URL') . '/v1/transaction/list',
            [
                'from' => '2022-04-01',
                'to' => '2022-06-30',
            ]
        );

        $lastest_transaction = LastestTransaction::where('id_user', $user->id)->first();
        if (!$lastest_transaction) {
            $lastest_transaction = new LastestTransaction;
            $lastest_transaction->id_user = $user->id;
            $lastest_transaction->transaction = $response->json();
            $lastest_transaction->save();
        } elseif ($lastest_transaction) {
            $lastest_transaction->transaction = $response->json();
            $lastest_transaction->save();
        }

        // processing data
        $data = $response;
        $collection = collect(json_decode($data, true));
        $data = $collection['data'];
        $income = 0;
        $expense = 0;
        foreach ($data as $dt) {
            $object = json_decode(json_encode($dt), FALSE);
            if ($object->direction == 'in') {
                $income += $object->amount;
            } else {
                $expense += $object->amount;
            }
        }
        $income *= 2;

        $user->profiling = $income - $expense;
        $user->save();

        // return success
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Updated, Please Contact Admin If You Have Any Problem',
            'profiling' => $user->profiling,
        ], 200);
    }
}
