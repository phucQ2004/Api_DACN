<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'CHI_TIET_DON_HANG';

    protected $primaryKey = 'ID_CTDH';

    protected $fillable = [
        'ID_don_hang',
        'ID_san_pham',
        'So_luong',
        'Gia',
        'Thanh_tien',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ID_don_hang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ID_san_pham');
    }
}
