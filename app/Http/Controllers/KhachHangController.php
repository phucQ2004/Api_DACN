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

    public function updateKhachHang(Request $request, $ID_khach_hang)
        {
            // Tìm khách hàng theo ID
            $khachhang = KhachHang::find($ID_khach_hang);

            if (!$khachhang) {
                return response()->json([
                    'success' => false,
                    'message' => "Khách hàng với ID $ID_khach_hang không tồn tại."
                ], 404);
            }

            // Xác thực dữ liệu gửi lên
            $validatedData = $request->validate([
                'Ten_day_du' => 'nullable|string|max:255',
                'So_dien_thoai' => 'nullable|string|max:15',
                'Ngay_sinh' => 'nullable|date',
                'Email' => 'nullable|email|max:255',
                'Dia_chi' => 'nullable|string|max:255'
            ]);

            // Cập nhật thông tin khách hàng
            $khachhang->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => "Thông tin khách hàng đã được cập nhật thành công.",
                'data' => $khachhang
            ]);
        }

}
