<?php
namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use Illuminate\Http\Request;

class ChiTietDonHangController extends Controller
{
    /**
 * Thêm chi tiết đơn hàng theo danh sách
 */
public function addOrderDetails(Request $request, $ID_don_hang)
{
    // Tìm đơn hàng
    $donHang = DonHang::find($ID_don_hang);
    if (!$donHang) {
        return response()->json([
            'success' => false,
            'message' => 'Đơn hàng không tồn tại.',
        ], 404);
    }

    // Lấy danh sách sản phẩm từ request
    $orderDetails = $request->input('selectedProducts', []); // Mảng chứa chi tiết sản phẩm

    if (empty($orderDetails)) {
        return response()->json([
            'success' => false,
            'message' => 'Vui lòng cung cấp danh sách sản phẩm.',
        ], 400);
    }

    $tongTien = $donHang->Tong_tien; // Lấy tổng tiền hiện tại

    foreach ($orderDetails as $detail) {
        $sanPham = SanPham::find($detail['ID_san_pham']);
        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại: ID ' . $detail['ID_san_pham'],
            ], 404);
        }

        // Thêm chi tiết đơn hàng
        $chiTietDonHang = new Chitietdonhang();
        $chiTietDonHang->ID_don_hang = $ID_don_hang;
        $chiTietDonHang->ID_san_pham = $detail['ID_san_pham'];
        $chiTietDonHang->So_luong = $detail['So_luong'];
        $chiTietDonHang->Gia = $sanPham->Gia;
        $chiTietDonHang->Thanh_tien = $detail['So_luong'] * $sanPham->Gia;
        $chiTietDonHang->save();

        // Cộng vào tổng tiền
        $tongTien += $chiTietDonHang->Thanh_tien;
    }

    // Cập nhật tổng tiền
    $donHang->Tong_tien = $tongTien;
    $donHang->save();

    return response()->json([
        'success' => true,
        'message' => 'Chi tiết đơn hàng đã được thêm.',
        'data' => $donHang,
    ]);
}

}

