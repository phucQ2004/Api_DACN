<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';
    protected $primaryKey = 'ID_don_hang';
    protected $fillable = [
        'ID_khach_hang', 'Ten_nguoi_nhan', 'Dia_chi', 'So_dien_thoai',
        'ID_DC','Tong_tien', 'Ngay_tao', 'Trang_thai', 'ID_phuong_thuc',
        'Ngay_thanh_toan', 'Trang_thai_thanh_toan', 'Trang_thai_don_hang'
    ];

    public $timestamps = false;

    // Quan hệ với Khách hàng
    public function khachhang()
    {
        return $this->belongsTo(KhachHang::class, 'ID_khach_hang', 'ID_khach_hang');
    }

    // Quan hệ với Chi tiết đơn hàng
    public function chitietdonhang()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ID_don_hang', 'ID_don_hang');
    }

    // Quan hệ với Phương thức thanh toán
    public function phuongthucthanhtoan()
    {
        return $this->belongsTo(PTTT::class, 'ID_phuong_thuc', 'ID_PTTT');
    }
}
