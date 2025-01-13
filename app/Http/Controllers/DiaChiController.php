<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaChi;

class DiaChiController extends Controller
{
    // Lấy danh sách địa chỉ
    public function getAll()
    {
        $diachi = DiaChi::all();

        return response()->json([
            'success' => true,
            'data' => $diachi
        ]);
    }

    // Lấy thông tin địa chỉ theo ID
    public function getDiaChi($ID_dia_chi)
    {
        $diachi = DiaChi::find($ID_dia_chi);

        if (!$diachi) {
            return response()->json([
                'success' => false,
                'message' => "$ID_dia_chi not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $diachi
        ]);
    }
}
