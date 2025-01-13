<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhMuc;

class DanhMucController extends Controller
{
    // Lấy danh sách danh mục
    public function getAll()
    {
        $danhmuc = DanhMuc::all();

        return response()->json([
            'success' => true,
            'data' => $danhmuc
        ]);
    }

    // Lấy thông tin danh mục theo ID
    public function getDanhMuc($ID_danh_muc)
    {
        $danhmuc = DanhMuc::find($ID_danh_muc);

        if (!$danhmuc) {
            return response()->json([
                'success' => false,
                'message' => "$ID_danh_muc not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $danhmuc
        ]);
    }
}
