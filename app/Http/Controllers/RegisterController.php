<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\KhachHang;
use App\Models\NguoiDung;
use App\Models\DiaChi;
class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validatedData = $request->validate([
                'Ten_dang_nhap' => 'required|unique:nguoi_dung,Ten_dang_nhap|max:255',
                'Mat_khau' => 'required|min:8',
                'isAdmin' => 'required|boolean',
            ]);

            // Tạo tài khoản người dùng mới
            $nguoiDung = NguoiDung::create([
                'Ten_dang_nhap' => $validatedData['Ten_dang_nhap'],
                'Mat_khau' => bcrypt($validatedData['Mat_khau']),
                'isAdmin' => $request->input('isAdmin', false),
            ]);

            // Tự động tạo thông tin khách hàng liên quan
            $nguoiDung->khachHang()->create([
                'Ten_day_du' => 'Chưa cập nhật',
                'So_dien_thoai' => 'Chưa cập nhật',
                'Ngay_sinh' => null,
                'Email' => 'Chưa cập nhật',
                'Dia_chi' => null,
            ]);

            // Thêm một địa chỉ mặc định
            $nguoiDung->khachHang->diaChi()->create([
                'Ten_day_du' => 'Chưa cập nhật',
                'So_dien_thoai' => 'Chưa cập nhật',
                'Dia_chi' => 'Chưa cập nhật',
                'Mac_dinh' => 1,
                'Mo_ta' => 'Chưa cập nhật',
            ]);

            // Trả về kết quả
            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'data' => $nguoiDung,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình đăng ký tài khoản.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

