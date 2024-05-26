<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklists extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title_checklists_id',
        'is_active',
    ];

    public function titleChecklists()
    {
        return $this->belongsTo(TitleChecklists::class);
    }
}