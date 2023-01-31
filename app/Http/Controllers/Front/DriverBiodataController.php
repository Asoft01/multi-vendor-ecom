<?php

namespace App\Http\Controllers;

use App\Http\Enums\HttpStatusCode;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\StorageManager;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\EditAdminRequest;
use App\Http\Requests\BioDataRequest;
use App\Models\DriverBiodata;
use App\Models\User;
use App\Models\UserGuid;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class DriverBiodataController extends Controller
{


    // public function editDriverBiodata(BioDataRequest $request): Response|Application|ResponseFactory
    // {
    //     $user_id = auth()->user()->id;
    //     $driver_license = $request->driver_license;
    //     $adher_card = $request->adher_card;
    //     $userIdentifier = UserGuid::with('user')->where(['user_id' => auth()->user()->id])->first();
    //     $user = $userIdentifier->user;
    //     if ($request->hasFile('driver_license') || $request->hasFile('adher_card')) {
    //         // dd("profile_image"); die;
    //         $driver_license = StorageManager::uploadFile($request->file('driver_license'), 'users');
    //         $adher_card = StorageManager::uploadFile($request->file('adher_card'), 'card');
    //         if (!empty($user->driver_license)) {
    //             StorageManager::deleteFile($user->driver_license);
    //         }
    //         if (!empty($user->adher_card)) {
    //             StorageManager::deleteFile($user->adher_card);
    //         }
    //     }
    //     $driver_biodata = DriverBiodata::where('user_id', auth()->user()->id)->count();
    //     if ($driver_biodata > 0) {
    //         DriverBiodata::where('user_id', auth()->user()->id)->update(['user_id' => $user_id, 'vehicle_type' => $request->vehicle_type, 'vehicle_manufacturer' => $request->vehicle_manufacturer, 'vehicle_model_no' => $request->vehicle_model_no, 'vehicle_license_no' => $request->vehicle_license_no, 'driver_license' => $driver_license, 'adher_card' => $adher_card]);

    //         return ApiResponse::send(false, "Account for " . auth()->user()->first_name . " updated successfully", null, HttpStatusCode::SUCCESS);
    //     } else {
    //         return ApiResponse::send(true, "no biodata found", null, HttpStatusCode::ERROR);
    //     }
    // }
}
