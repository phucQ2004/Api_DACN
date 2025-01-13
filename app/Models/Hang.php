<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hang extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'ID_hang'; // Chỉ định khóa chính

    protected $table = "hang"; // Tên bảng trong CSDL

    // Quan hệ với bảng SanPham
    public function sanPham()
    {
        return $this->hasMany(SanPham::class, 'ID_hang', 'ID_hang');
    }

    // Các cột cho phép gán giá trị
    protected $fillable = ['Ten_hang', 'Mo_ta'];

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}