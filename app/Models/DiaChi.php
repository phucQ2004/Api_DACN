<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaChi extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID_dia_chi'; // Chỉ định khóa chính

    protected $table = "dia_chi"; // Tên bảng trong CSDL

    // Quan hệ với bảng KhachHang
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ID_khach_hang', 'ID_khach_hang');
    }

    // Các cột cho phép gán giá trị
    // protected $fillable = ['ID_khach_hang', 'Ten_day_du', 'So_dien_thoai', 'Dia_chi', 'Mac_dinh', 'Mo_ta'];

    protected $fillable = [
        'id_khach_hang',
        'ten_day_du',
        'so_dien_thoai',
        'dia_chi',
        'mac_dinh',
        'mo_ta',
    ];
    

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}
