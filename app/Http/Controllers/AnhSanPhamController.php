<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnhSanPham;

class AnhSanPhamController extends Controller
{
    // Lấy danh sách ảnh
    public function getAll()
    {
        $anhsanpham = AnhSanPham::all();

        return response()->json([
            'success' => true,
            'data' => $anhsanpham 
        ]);
    }

    
    // Lấy thông tin ảnh theo ID
    public function getAnh($ID_anh)
    {
        $anhsanpham = AnhSanPham::find($ID_anh);

        if (!$anhsanpham ) {
            return response()->json([
                'success' => false,
                'message' => "$ID_anh not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' =>   $anhsanpham
        ]);
    }

}
