<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanPham extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID_san_pham'; // Chỉ định khóa chính

    protected $table = "san_pham"; // Tên bảng trong CSDL


    //Thêm mối quan anhSanPham
    public function anhSanPham()
    {
        return $this->hasMany(AnhSanPham::class, 'ID_san_pham', 'ID_san_pham');
    }

     // Quan hệ với bảng Hang
     public function hang()
     {
         return $this->belongsTo(Hang::class, 'ID_hang', 'ID_hang');
     }   
     
     // Quan hệ với bảng Danh Mục
     public function danhMuc()
     {
         return $this->belongsTo(DanhMuc::class, 'ID_danh_muc', 'ID_danh_muc');
     }

    // Các cột cho phép gán giá trị
    protected $fillable = ['Ten', 'Mo_ta', 'So_luong_ton', 'ID_hang', 'ID_danh_muc', 'Trang_thai', 'Thuong_hieu', 'Chat_lieu_day', 'Kich_thuoc_mat', 'Chong_nuoc', 'Bao_hanh'];

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}