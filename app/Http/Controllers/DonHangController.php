<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;
use App\Models\Giohang;
use App\Models\Chitietgiohang;
use App\Models\KhachHang;

class DonHangController extends Controller
{
    /**
     * Tạo đơn hàng từ giỏ hàng
     */
    public function createOrder(Request $request, $ID_khach_hang)
    {
        // Kiểm tra giỏ hàng
        $giohang = Giohang::where('ID_khach_hang', $ID_khach_hang)->first();
        if (!$giohang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại.',
            ], 404);
        }

        $chiTietGioHang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)->get();
        if ($chiTietGioHang->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống.',
            ], 400);
        }

        // Tạo đơn hàng
        $donHang = new DonHang();
        $donHang->ID_khach_hang = $ID_khach_hang;
        $donHang->Ten_nguoi_nhan = $request->input('Ten_nguoi_nhan');
        $donHang->Dia_chi = $request->input('Dia_chi');
        $donHang->So_dien_thoai = $request->input('So_dien_thoai');
        $donHang->ID_phuong_thuc = $request->input('ID_phuong_thuc');
        $donHang->Ngay_tao = now();
        $donHang->Trang_thai = 'Chờ xác nhận';
        $donHang->Trang_thai_thanh_toan = 'Chưa thanh toán';
        $donHang->Tong_tien = 0; // Sẽ cập nhật sau
        $donHang->save();

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được tạo.',
            'data' => $donHang,
        ]);
    }

    /**
     * Xem danh sách đơn hàng của khách hàng
     */
    public function viewOrders($ID_khach_hang)
    {
        $donHang = DonHang::where('ID_khach_hang', $ID_khach_hang)->get();
        if ($donHang->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có đơn hàng nào.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donHang,
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus(Request $request, $ID_don_hang)
    {
        $donHang = DonHang::find($ID_don_hang);
        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không tồn tại.',
            ], 404);
        }

        $donHang->Trang_thai = $request->input('Trang_thai', $donHang->Trang_thai);
        $donHang->Trang_thai_thanh_toan = $request->input('Trang_thai_thanh_toan', $donHang->Trang_thai_thanh_toan);
        $donHang->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đơn hàng đã được cập nhật.',
            'data' => $donHang,
        ]);
    }
}
