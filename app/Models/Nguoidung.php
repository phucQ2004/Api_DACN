<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nguoidung extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'nguoi_dung';

    // Chỉ định khóa chính
    protected $primaryKey = 'ID_nguoi_dung';

    // Các cột cho phép gán giá trị
    protected $fillable = ['Ten_dang_nhap', 'Mat_khau', 'isAdmin'];

    // Bỏ timestamps nếu bảng không có các cột created_at và updated_at
    public $timestamps = false;

    /**
     * Mối quan hệ 1-1 với bảng KhachHang
     */
    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'ID_khach_hang', 'ID_nguoi_dung');
    }
}