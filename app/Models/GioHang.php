<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giohang extends Model
{
    use HasFactory;

    protected $table = 'gio_hang';
    protected $primaryKey = 'ID_gio_hang';
    protected $fillable = ['ID_khach_hang'];

    public $timestamps = false;

    // Quan hệ với Chi tiết giỏ hàng
    public function chitietgiohang()
    {
        return $this->hasMany(Chitietgiohang::class, 'ID_gio_hang', 'ID_gio_hang');
    }

    // Quan hệ với Khách hàng
    public function khachhang()
    {
        return $this->belongsTo(Khachhang::class, 'ID_khach_hang', 'ID_khach_hang');
    }
    public function donhang()
    { 
        return $this->hasMany(DonHang::class, 'ID_khach_hang', 'ID_khach_hang'); 
    }
}
