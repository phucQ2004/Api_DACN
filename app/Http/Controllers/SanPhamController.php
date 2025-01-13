<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\Chitietgiohang;

class SanPhamController extends Controller
{
    // Lấy danh sách sản phẩm
    public function getAll()
    {
        // $sanpham = SanPham::all();
        $sanpham = SanPham::with(['anhSanPham', 'hang','danhMuc'])->get();


        return response()->json([
            'success' => true,
            'data' => $sanpham
        ]);
    }
    
        // Lấy thông tin sản phẩm theo ID
        public function getSanPham($ID_san_pham)
    {
        // Lấy sản phẩm và các ảnh liên quan
        $sanpham = SanPham::with(['anhSanPham', 'hang','danhMuc']) // Quan hệ đã được định nghĩa trong model SanPham
                        ->find($ID_san_pham);

        // Kiểm tra nếu sản phẩm không tồn tại
        if (!$sanpham) {
            return response()->json([
                'success' => false,
                'message' => "Sản phẩm với ID $ID_san_pham không tồn tại."
            ], 404);
        }

        // Trả về thông tin sản phẩm và ảnh
        return response()->json([
            'success' => true,
            'data' => $sanpham
        ]);
    }
}
