<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietgiohang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_gio_hang';
    protected $primaryKey = 'ID_chi_tiet_gio_hang';
    protected $fillable = ['ID_gio_hang', 'ID_san_pham', 'So_luong_SP'];

    public $timestamps = false;

    // Quan hệ với Giỏ hàng
    public function giohang()
    {
        return $this->belongsTo(Giohang::class, 'ID_gio_hang', 'ID_gio_hang');
    }

    // Quan hệ với Sản phẩm
    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'ID_san_pham', 'ID_san_pham');
    }
}
