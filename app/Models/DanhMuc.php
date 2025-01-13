<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhMuc extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'ID_danh_muc'; // Chỉ định khóa chính

    protected $table = "danh_muc_san_pham"; // Tên bảng trong CSDL

    // Quan hệ với bảng SanPham
    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'ID_danh_muc', 'ID_danh_muc');
    }

    // Các cột cho phép gán giá trị
    protected $fillable = ['Ten_danh_muc'];

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}