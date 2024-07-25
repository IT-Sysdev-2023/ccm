<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckDetailResource;
use App\Models\Checks;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    //
    public function details($id)
    {
        $data = Checks::with('customer:customer_id,fullname', 'bank:bank_id,bankbranchname', 'department:department_id,department')
            ->where('checks_id', $id)
            ->selectFilter()
            ->first();

        $resource = new CheckDetailResource($data);

        return response()->json($resource);
    }
}
