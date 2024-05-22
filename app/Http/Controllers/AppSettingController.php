<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\AppSetting;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppSettingController extends Controller
{
    //
    public function appConfigIndex()
    {

        $appSetting = BusinessUnit::get();

        $appInstIp = AppSetting::where('app_key', 'app_institutionalcheck_ip')->first();

        return Inertia::render('MasterFile/AppSettingFile', [
            'data' => $appSetting,
            'columns' => ColumnsHelper::$app_setting_columns,
            'appKey' => $appInstIp,
        ]);
        return redirect()->route('app.config');
    }
    public function updateSettings(Request $request)
    {
        // dd($request->all());
        BusinessUnit::where('businessunit_id', $request->bunitId)->update([
            'loc_code_atp' => $request->loc_code_atp,
            'b_encashstart' => $request->b_encashstart,
            'b_atpgetdata' => $request->b_atpgetdata,
        ]);
    }

    public function updateInsti(Request $request)
    {
        AppSetting::where('app_id', $request->app_id)->update([
            'app_value' => $request->app_value,
        ]);
        return redirect()->route('app.config');
    }
}
