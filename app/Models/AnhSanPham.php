<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnhSanPham extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID_anh'; // Chỉ định khóa chính

    protected $table = "anh_san_pham"; // Tên bảng trong CSDL


    // Quan hệ với bảng SanPham
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ID_san_pham', 'ID_san_pham');
    }

    // Các cột cho phép gán giá trị
    protected $fillable = ['Link_anh', 'Thu_tu_anh', 'ID_san_pham'];

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}