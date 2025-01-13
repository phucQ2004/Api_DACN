<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PTTT extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'ID_PTTT'; // Chỉ định khóa chính

    protected $table = "pttt"; // Tên bảng trong CSDL

    // Các cột cho phép gán giá trị
    protected $fillable = ['Ten', 'Mo_ta'];

    public $timestamps = false; // Bỏ timestamps nếu bảng không có created_at và updated_at
}