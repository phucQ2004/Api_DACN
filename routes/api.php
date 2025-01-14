<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\PTTTController;
use App\Http\Controllers\NguoidungController;
use App\Http\Controllers\AnhSanPhamController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\DiaChiController;
use App\Http\Controllers\HangController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\ChitietGiohangController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//Bảng người dùng
Route::prefix('nguoidung')->group(function () {
    Route::get('all', [NguoidungController::class, 'getAll']); // Lấy danh sách người dùng
    Route::get('{ID_nguoi_dung}', [NguoidungController::class, 'getUser']); // Lấy người dùng theo ID
    Route::put('update/{id}', [NguoidungController::class, 'update']); // Cập nhật người dùng
});

//Bảng ảnh sản phẩm
Route::prefix('anhsanpham')->group(function () {
    Route::get('all', [AnhSanPhamController::class, 'getAll']); // Lấy danh sách ảnh
    Route::get('{ID_anh}', [AnhSanPhamController::class, 'getAnh']); // Lấy ảnh theo ID
});

//Bảng danh mục
Route::prefix('danhmuc')->group(function () {
    Route::get('all', [DanhMucController::class, 'getAll']); // Lấy danh sách ảnh
    Route::get('{ID_danh_muc}', [DanhMucController::class, 'getDanhMuc']); // Lấy danh mục theo ID
});

//Bảng địa chỉ
Route::prefix('diachi')->group(function () {
    Route::get('all', [DiaChiController::class, 'getAll']); // Lấy danh sách dịa chỉ
    Route::get('{ID_dia_chi}', [DiaChiController::class, 'getDiaChi']); // Lấy địa chỉ theo ID
    Route::post('/add/{ID_khach_hang}', [DiaChiController::class, 'addDiaChi']);
    Route::put('/update/{ID_dia_chi}', [DiaChiController::class, 'updateDiaChi']);
    Route::get('/all/{ID_khach_hang}', [DiaChiController::class, 'getDiaChiByKhachHang']);
    Route::delete('/delete/{ID_dia_chi}', [DiaChiController::class, 'deleteDiaChi']);

});

//Bảng hãng
Route::prefix('hang')->group(function () {
    Route::get('all', [HangController::class, 'getAll']); // Lấy danh sách hãng
    Route::get('{ID_hang}', [HangController::class, 'getHang']); // Lấy hãng theo ID
});

//Bảng khách hàng
Route::prefix('khachhang')->group(function () {
    Route::get('all', [KhachHangController::class, 'getAll']); // Lấy danh sách khách hàng
    Route::get('{ID_khach_hang}', [KhachHangController::class, 'getKhachHang']); // Lấy khách hàng theo ID
});

//Bảng phương thức thanh toán
Route::prefix('pttt')->group(function () {
    Route::get('all', [PTTTController::class, 'getAll']); // Lấy danh sách phương thức thanh toán
    Route::get('{ID_PTTT}', [PTTTController::class, 'getPTTT']); // Lấy phương thức thanh theo ID
});

//Bảng sản phẩm
Route::prefix('sanpham')->group(function () {
    Route::get('all', [SanPhamController::class, 'getAll']); // Lấy danh sách sản phẩm
    Route::get('{ID_san_pham}', [SanPhamController::class, 'getSanPham']); // Lấy sản phẩm theo ID
});

// Kiểm tra đăng nhập
Route::post('/login', [LoginController::class, 'login']);

// Kiểm tra tên đăng nhập và đăng ký
Route::post('/register', [RegisterController::class, 'register']);
//// Giỏ Hàng
Route::prefix('gio-hang')->group(function () {
    // Thêm sản phẩm vào giỏ hàng
    Route::post('/them/{ID_khach_hang}/{ID_san_pham}', [GioHangController::class, 'addToCart']);
    
    // Xóa sản phẩm khỏi giỏ hàng
    Route::delete('/xoa/{ID_khach_hang}/{ID_san_pham}', [GioHangController::class, 'removeFromCart']);
    
    // Xem giỏ hàng
    Route::get('/xem/{ID_khach_hang}', [GioHangController::class, 'viewCart']);
    //Cập nhậtnhật
    Route::put('/cap-nhat/{ID_khach_hang}/{ID_san_pham}', [GioHangController::class, 'updateCartItem']);
});

// ==================== Routes cho ChitietGiohang ====================

// Thêm sản phẩm vào chi tiết giỏ hàng (nếu cần route riêng)
Route::post('/chitietgiohang/{ID_gio_hang}/{ID_san_pham}', [ChitietGiohangController::class, 'addProductToCartDetails']);

// Xóa sản phẩm khỏi chi tiết giỏ hàng
Route::delete('/chitietgiohang/{ID_gio_hang}/{ID_san_pham}', [ChitietGiohangController::class, 'removeProductFromCartDetails']);

// Xem sản phẩm trong chi tiết giỏ hàng
Route::get('/chitietgiohang/{ID_gio_hang}', [ChitietGiohangController::class, 'viewCartDetails']);


