<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklists extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'title_checklists_id',
        'position',
        'created_at'
    ];

    public function titleChecklists()
    {
        return $this->belongsTo(TitleChecklists::class);
    }

    public function titleChecklist()
    {
        return $this->belongsTo(TitleChecklists::class, 'title_checklists_id');
    }
}