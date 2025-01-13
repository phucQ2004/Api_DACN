<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chitietgiohang;
use App\Models\Giohang;
use App\Models\Sanpham;

class GioHangController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request, $ID_khach_hang, $ID_san_pham)
    {
        // Kiểm tra xem sản phẩm có tồn tại không
        $sanpham = Sanpham::find($ID_san_pham);
        if (!$sanpham) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại.',
            ], 404);
        }

        // Tìm hoặc tạo mới giỏ hàng cho khách hàng
        $giohang = Giohang::firstOrCreate(['ID_khach_hang' => $ID_khach_hang]);
        $soLuongSP = $request->input('So_luong_SP', 1); // Số lượng sản phẩm mặc định là 1 nếu không truyền

        // Ghi nhật ký để kiểm tra dữ liệu đầu vào
        \Log::info('Adding product to cart', [
            'ID_khach_hang' => $ID_khach_hang,
            'ID_san_pham' => $ID_san_pham,
            'So_luong_SP' => $soLuongSP
        ]);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)
            ->where('ID_san_pham', $ID_san_pham)
            ->first();

        if ($chitietgiohang) {
            // Nếu sản phẩm đã tồn tại, tăng số lượng
            $chitietgiohang->So_luong_SP += $soLuongSP;
        } else {
            // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
            $chitietgiohang = new Chitietgiohang();
            $chitietgiohang->ID_gio_hang = $giohang->ID_gio_hang;
            $chitietgiohang->ID_san_pham = $ID_san_pham;
            $chitietgiohang->So_luong_SP = $soLuongSP;
        }

        $chitietgiohang->save();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
            'data' => $chitietgiohang,
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart($ID_khach_hang, $ID_san_pham)
    {
        // Tìm giỏ hàng của khách hàng
        $giohang = Giohang::where('ID_khach_hang', $ID_khach_hang)->first();
        if (!$giohang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại.',
            ], 404);
        }

        // Tìm chi tiết sản phẩm trong giỏ hàng
        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)
            ->where('ID_san_pham', $ID_san_pham)
            ->first();

        if (!$chitietgiohang) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
            ], 404);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $chitietgiohang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.',
        ]);
    }

    /**
     * Xem giỏ hàng của khách hàng
     */
    public function viewCart($ID_khach_hang)
    {
        // Tìm giỏ hàng của khách hàng
        $giohang = Giohang::where('ID_khach_hang', $ID_khach_hang)->first();
        if (!$giohang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại.',
            ], 404);
        }

        // Lấy chi tiết giỏ hàng kèm thông tin sản phẩm và ảnh sản phẩm
        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)
            ->with(['sanpham' => function ($query) {
                $query->with(['anhSanPham']); // Include mối quan hệ với bảng anhSanPham
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $chitietgiohang,
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCartItem(Request $request, $ID_khach_hang, $ID_san_pham)
    {
        // Tìm giỏ hàng của khách hàng
        $giohang = Giohang::where('ID_khach_hang', $ID_khach_hang)->first();
        if (!$giohang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại.',
            ], 404);
        }

        // Tìm chi tiết sản phẩm trong giỏ hàng
        $chitietgiohang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)
            ->where('ID_san_pham', $ID_san_pham)
            ->first();

        if (!$chitietgiohang) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
            ], 404);
        }

        // Cập nhật số lượng sản phẩm
        $soLuongMoi = $request->input('So_luong_SP');
        if ($soLuongMoi <= 0) {
            // Nếu số lượng mới <= 0, xóa sản phẩm khỏi giỏ hàng
            $chitietgiohang->delete();
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng do số lượng <= 0.',
            ]);
        }

        $chitietgiohang->So_luong_SP = $soLuongMoi;
        $chitietgiohang->save();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được cập nhật.',
            'data' => $chitietgiohang,
        ]);
    }
}
