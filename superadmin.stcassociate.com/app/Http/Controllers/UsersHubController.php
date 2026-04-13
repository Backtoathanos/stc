<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\UserElectronics;
use App\UserTrading;
use App\UserGroceries;
use App\UserManager;
use App\UserSchool;
use App\UserSite;
use App\UserAdmin;
use App\City;
use App\State;

class UsersHubController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Users';
        $data['electronicsRecords'] = UserElectronics::getAdmin();
        $data['tradingRecords'] = UserTrading::getAdmin();
        $data['groceriesRecords'] = UserGroceries::getAdmin();
        $data['managerRecords'] = UserManager::getAdmin();
        $data['schoolRecords'] = UserSchool::getAdmin();
        $data['siteRecords'] = UserSite::getAdmin();
        $data['uadminRecords'] = UserAdmin::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        $data['getRecordManager'] = UserManager::getManagerForSite();
        $data['tradingBranchLocations'] = $this->tradingBranchLocationOptions();

        return view('pages.usershub', $data);
    }

    public function recordJson(string $type, $id)
    {
        $allowed = ['electronics', 'trading', 'groceries', 'manager', 'school', 'site', 'uadmin'];
        if (! in_array($type, $allowed, true)) {
            abort(404);
        }

        switch ($type) {
            case 'electronics':
                $row = UserElectronics::getSingle($id);
                break;
            case 'trading':
                $row = UserTrading::getSingle($id);
                break;
            case 'groceries':
                $row = UserGroceries::getSingle($id);
                break;
            case 'manager':
                $row = UserManager::getSingle($id);
                break;
            case 'school':
                $row = UserSchool::getSingle($id);
                break;
            case 'site':
                $row = UserSite::getSingle($id);
                break;
            case 'uadmin':
                $row = UserAdmin::getSingle($id);
                break;
            default:
                abort(404);
        }

        if (! $row) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json($row);
    }

    protected function tradingBranchLocationOptions(): array
    {
        $fromShops = DB::table('stc_shop')
            ->whereNotNull('shopname')
            ->where('shopname', '!=', '')
            ->distinct()
            ->orderBy('shopname')
            ->pluck('shopname');

        $fromTrading = DB::table('stc_trading_user')
            ->whereNotNull('stc_trading_user_location')
            ->where('stc_trading_user_location', '!=', '')
            ->distinct()
            ->orderBy('stc_trading_user_location')
            ->pluck('stc_trading_user_location');

        $merged = $fromShops->merge($fromTrading)->unique();

        if (Schema::hasTable('stc_shop') && Schema::hasColumn('stc_shop', 'branch')) {
            $fromBranch = DB::table('stc_shop')
                ->whereNotNull('branch')
                ->where('branch', '!=', '')
                ->distinct()
                ->orderBy('branch')
                ->pluck('branch');
            $merged = $fromBranch->merge($merged)->unique();
        }

        return $merged->sort()->values()->all();
    }
}
