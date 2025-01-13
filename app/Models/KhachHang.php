<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhachHang extends Model
{
    use HasFactory;

    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'khach_hang';

    // Chỉ định khóa chính
    protected $primaryKey = 'ID_khach_hang';

    // Các cột cho phép gán giá trị
    protected $fillable = ['ID_khach_hang', 'Ten_day_du', 'So_dien_thoai', 'Ngay_sinh', 'Email'];

    // Bỏ timestamps nếu bảng không có các cột created_at và updated_at
    public $timestamps = false;

    /**
     * Mối quan hệ 1-1 với bảng Nguoidung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(Nguoidung::class, 'ID_khach_hang', 'ID_nguoi_dung');
    }

    /**
     * Mối quan hệ 1-N với bảng DiaChi
     */
    public function diaChi()
    {
        return $this->hasMany(DiaChi::class, 'ID_khach_hang', 'ID_khach_hang');
    }
}

