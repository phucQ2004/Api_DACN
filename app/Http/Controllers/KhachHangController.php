<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhachHang;

class KhachHangController extends Controller
{
    // Lấy danh sách khách hàng
    public function getAll()
    {
        // $khachhang = KhachHang::all();
        $khachhang = KhachHang::with(['diaChi'])->get();

        return response()->json([
            'success' => true,
            'data' => $khachhang
        ]);
    }

    // Lấy thông tin danh mục theo ID
    public function getKhachHang($ID_khach_hang)
    {
        $khachhang = KhachHang::with(['diaChi'])
                                ->find($ID_khach_hang);

        if (!$khachhang) {
            return response()->json([
                'success' => false,
                'message' => "$ID_khach_hang not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $khachhang
        ]);
    }
}
