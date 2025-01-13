<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hang;

class HangController extends Controller
{
    // Lấy danh sách hãng
    public function getAll()
    {
        $hang = Hang::all();

        return response()->json([
            'success' => true,
            'data' => $hang
        ]);
    }

    // Lấy thông tin hãng theo ID
    public function getHang($ID_hang)
    {
        $hang = Hang::find($ID_hang);

        if (!$hang) {
            return response()->json([
                'success' => false,
                'message' => "$ID_hang not found."
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $hang
        ]);
    }
}
