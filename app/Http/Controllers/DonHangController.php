<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;
use App\Models\Giohang;
use App\Models\Chitietgiohang;
use App\Models\Chitietdonhang; // Thêm model ChiTietDonHang
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
    
        // Lấy danh sách ID sản phẩm được chọn từ request
        $selectedProductIDs = $request->input('selectedProductIDs', []);
        if (empty($selectedProductIDs)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn phải chọn ít nhất một sản phẩm.',
            ], 400);
        }
    
        // Lấy sản phẩm trong giỏ hàng với các ID đã chọn
        $chiTietGioHang = Chitietgiohang::where('ID_gio_hang', $giohang->ID_gio_hang)
                                        ->whereIn('ID_san_pham', $selectedProductIDs)
                                        ->get();
        if ($chiTietGioHang->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có sản phẩm nào được chọn trong giỏ hàng.',
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
        $donHang->ID_DC = $request->input('ID_DC') ?? 1; // Giá trị mặc định
        $donHang->Tong_tien = 0; // Thiết lập giá trị mặc định cho tổng tiền
        $donHang->save();
    
        // Thêm chi tiết đơn hàng và tính tổng tiền
        $tongTien = 0; // Khởi tạo tổng tiền
    
        foreach ($chiTietGioHang as $chiTiet) {
            // Tạo chi tiết đơn hàng
            $chiTietDonHang = new Chitietdonhang();
            $chiTietDonHang->ID_don_hang = $donHang->ID_don_hang;
            $chiTietDonHang->ID_san_pham = $chiTiet->ID_san_pham;
            $chiTietDonHang->So_luong = $chiTiet->So_luong_SP;
            $chiTietDonHang->Gia = $chiTiet->sanpham->Gia;
            $chiTietDonHang->Thanh_tien = $chiTietDonHang->So_luong * $chiTietDonHang->Gia;
            $chiTietDonHang->save();
    
            // Cộng vào tổng tiền
            $tongTien += $chiTietDonHang->Thanh_tien;
        }
    
        // Cập nhật tổng tiền của đơn hàng
        $donHang->Tong_tien = $tongTien;
        $donHang->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã được tạo với các sản phẩm được chọn.',
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
