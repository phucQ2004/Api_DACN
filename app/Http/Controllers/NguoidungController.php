<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Nguoidung;

class NguoidungController extends Controller
{
    // Lấy danh sách người dùng
    public function getAll()
    {
        $nguoidung = Nguoidung::all();

        return response()->json([
            'success' => true,
            'data' => $nguoidung
        ]);
    }

    // Lấy thông tin người dùng theo ID
    public function getUser($ID_nguoi_dung)
    {
        $nguoidung = Nguoidung::find($ID_nguoi_dung);

        if (!$nguoidung) {
            return response()->json([
                'success' => false,
                'message' => "User with ID $ID_nguoi_dung not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $nguoidung
        ]);
    }
    // Cập nhật thông tin người dùng
    public function update(Request $request, $ID_nguoi_dung)
    {
        $nguoidung = Nguoidung::find($ID_nguoi_dung);

        if (!$nguoidung) {
            return response()->json([
                'success' => false,
                'message' => "User with ID $ID_nguoi_dung not found."
            ]);
        }

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'Ten_dang_nhap' => 'required|max:255|unique:nguoi_dung,Ten_dang_nhap,' . $ID_nguoi_dung . ',ID_NGUOI_DUNG',
            'Mat_khau' => 'nullable|min:6',
            'isAdmin' => 'required|boolean'
        ]);

        // Cập nhật thông tin
        $nguoidung->Ten_dang_nhap = $validated['Ten_dang_nhap'];
        if (!empty($validated['Mat_khau'])) {
            $nguoidung->Mat_khau = bcrypt($validated['Mat_khau']);
        }
        $nguoidung->isAdmin = $validated['isAdmin'];
        $nguoidung->save();

        return response()->json([
            'success' => true,
            'message' => "User updated successfully!",
            'data' => $nguoidung
        ]);
    }
}
