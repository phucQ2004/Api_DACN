<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'Ten_dang_nhap' => 'required|string',
            'Mat_khau' => 'required|string',
        ]);

        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $nguoiDung = NguoiDung::where('Ten_dang_nhap', $request->Ten_dang_nhap)->first();

        if (!$nguoiDung) {
            return response()->json([
                'success' => false,
                'message' => 'Tên đăng nhập không tồn tại',
            ]);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->Mat_khau, $nguoiDung->Mat_khau)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không chính xác',
            ]);
        }

        // Phản hồi thành công
        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'ID_nguoi_dung' => $nguoiDung->ID_nguoi_dung,
                'Ten_dang_nhap' => $nguoiDung->Ten_dang_nhap,
            ],
        ]);
    }

}
