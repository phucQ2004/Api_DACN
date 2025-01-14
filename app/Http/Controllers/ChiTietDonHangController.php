<?php
namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use Illuminate\Http\Request;

class ChiTietDonHangController extends Controller
{
    public function viewOrderDetails($ID_don_hang)
    {
        $chiTietDonHang = ChiTietDonHang::where('ID_don_hang', $ID_don_hang)
            ->with('sanPham')
            ->get();

        return response()->json($chiTietDonHang);
    }
}

