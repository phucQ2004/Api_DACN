<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chitietgiohang;
use App\Models\Giohang;
use App\Models\Sanpham;

class ChitietGioHangController extends Controller
{
    // Thêm hoặc cập nhật sản phẩm vào chi tiết giỏ hàng
    public function addOrUpdateProduct(Request $request, $ID_gio_hang, $ID_san_pham)
    {
        $giohang = Giohang::find($ID_gio_hang);
        $sanpham = Sanpham::find($ID_san_pham);
        $soLuongSP = $request->input('So_luong_SP', 1);

        if (!$giohang || !$sanpham) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng hoặc sản phẩm không tồn tại.'
            ]);
        }
        \Log::info('Adding product to cart', [ 'ID_gio_hang' => $ID_gio_hang, 'ID_san_pham' => $ID_san_pham, 'So_luong_SP' => $soLuongSP]);

        $chitietgiohang = Chitietgiohang::updateOrCreate(
            ['ID_gio_hang' => $ID_gio_hang, 'ID_san_pham' => $ID_san_pham],
            ['So_luong_SP' => $request->input('So_luong_SP', 1)]
        );

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm hoặc cập nhật trong chi tiết giỏ hàng.',
            'data' => $chitietgiohang
        ]);
    }

    // Xóa sản phẩm khỏi chi tiết giỏ hàng
    public function removeProduct($ID_gio_hang, $ID_san_pham)
    {
        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $ID_gio_hang)
            ->where('ID_san_pham', $ID_san_pham)
            ->first();

        if ($chitietgiohang) {
            $chitietgiohang->delete();
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi chi tiết giỏ hàng.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong chi tiết giỏ hàng.'
        ]);
    }

    // Lấy danh sách chi tiết giỏ hàng theo ID giỏ hàng
    public function getCartDetails($ID_gio_hang)
    {
        $giohang = Giohang::find($ID_gio_hang);

        if (!$giohang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại.'
            ]);
        }

        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $ID_gio_hang)
            ->with('sanpham')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $chitietgiohang
        ]);
    }
}
