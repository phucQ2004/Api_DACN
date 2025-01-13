<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PTTT;

class PTTTController extends Controller
{
    // Lấy danh sách PTTT
    public function getAll()
    {
        $pttt = PTTT::all();

        return response()->json([
            'success' => true,
            'data' => $pttt
        ]);
    }

    // Lấy thông tin PTTT theo ID
    public function getPTTT( $ID_PTTT)
    {
        $pttt = PTTT::find($ID_PTTT);

        if (!$pttt) {
            return response()->json([
                'success' => false,
                'message' => "$ID_PTTT not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' =>  $pttt
        ]);
    }
}
