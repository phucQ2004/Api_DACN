<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KhachHang;
use App\Models\DiaChi;

class DiaChiController extends Controller
{
    // Lấy danh sách địa chỉ
    public function getAll()
    {
        $diachi = DiaChi::all();

        return response()->json([
            'success' => true,
            'data' => $diachi
        ]);
    }

    // Lấy thông tin địa chỉ theo ID
    public function getDiaChi($ID_dia_chi)
    {
        $diachi = DiaChi::find($ID_dia_chi);

        if (!$diachi) {
            return response()->json([
                'success' => false,
                'message' => "$ID_dia_chi not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $diachi
        ]);
    }

    public function addDiaChi(Request $request, $id_khach_hang)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ten_day_du' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:15',
            'dia_chi' => 'required|string|max:255',
            'mac_dinh' => 'nullable|boolean',
            'mo_ta' => 'nullable|string|max:255',
        ]);

        // Kiểm tra khách hàng có tồn tại
        $khachHang = KhachHang::find($id_khach_hang);
        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => "Khách hàng với ID $id_khach_hang không tồn tại."
            ], 404);
        }

        // Sử dụng transaction
        DB::beginTransaction();
        try {
            // Nếu Mac_dinh = 1, cập nhật các địa chỉ hiện tại thành Mac_dinh = 0
            if ($validated['mac_dinh'] ?? 0) {
                DiaChi::where('id_khach_hang', $id_khach_hang)
                    ->update(['mac_dinh' => 0]);
            }

            // Tạo địa chỉ mới
            $diaChi = DiaChi::create([
                'id_khach_hang' => $id_khach_hang,
                'ten_day_du' => $validated['ten_day_du'],
                'so_dien_thoai' => $validated['so_dien_thoai'],
                'dia_chi' => $validated['dia_chi'],
                'mac_dinh' => $validated['mac_dinh'] ?? 0,
                'mo_ta' => $validated['mo_ta'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Địa chỉ mới được thêm thành công.',
                'data' => $diaChi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDiaChiByKhachHang($id_khach_hang)
    {
        // Tìm tất cả địa chỉ của khách hàng theo id_khach_hang
        $diachi = DiaChi::where('id_khach_hang', $id_khach_hang)->get();

        if ($diachi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Không tìm thấy địa chỉ cho khách hàng với ID $id_khach_hang."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $diachi
        ]);
    }



    public function updateDiaChi(Request $request, $ID_dia_chi)
    {
        // Kiểm tra địa chỉ có tồn tại không
        $diaChi = DiaChi::find($ID_dia_chi);
        if (!$diaChi) {
            return response()->json([
                'success' => false,
                'message' => "Địa chỉ với ID $ID_dia_chi không tồn tại."
            ], 404);
        }
    
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ten_day_du' => 'nullable|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string|max:255',
            'mac_dinh' => 'nullable|boolean', // Thêm trường mac_dinh
        ]);
    
        // Cập nhật thông tin địa chỉ
        $diaChi->ten_day_du = $validated['ten_day_du'] ?? $diaChi->ten_day_du;
        $diaChi->so_dien_thoai = $validated['so_dien_thoai'] ?? $diaChi->so_dien_thoai;
        $diaChi->dia_chi = $validated['dia_chi'] ?? $diaChi->dia_chi;
        $diaChi->mo_ta = $validated['mo_ta'] ?? $diaChi->mo_ta;
    
        // Nếu trường 'mac_dinh' được cung cấp và có giá trị là true, cập nhật địa chỉ mặc định
        if (isset($validated['mac_dinh']) && $validated['mac_dinh'] === true) {
            // Đảm bảo chỉ có một địa chỉ mặc định, nếu có, chuyển các địa chỉ khác thành không mặc định
            DiaChi::where('id_nguoi_dung', $diaChi->id_nguoi_dung)
                  ->where('id_dia_chi', '!=', $diaChi->id_dia_chi)
                  ->update(['mac_dinh' => false]);
    
            $diaChi->mac_dinh = true;
        }
    
        // Lưu lại thay đổi
        $diaChi->save();
        $diaChi->refresh(); // Cập nhật lại dữ liệu từ database
    
        return response()->json([
            'success' => true,
            'message' => 'Địa chỉ được cập nhật thành công.',
            'data' => $diaChi
        ]);
    }
    
    public function deleteDiaChi($ID_dia_chi)
{
    // Kiểm tra địa chỉ có tồn tại không
    $diaChi = DiaChi::find($ID_dia_chi);
    if (!$diaChi) {
        return response()->json([
            'success' => false,
            'message' => "Địa chỉ với ID $ID_dia_chi không tồn tại."
        ], 404);
    }

    // Xóa địa chỉ
    $diaChi->delete();

    return response()->json([
        'success' => true,
        'message' => 'Địa chỉ đã được xóa thành công.'
    ]);
}


}
