<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleChecklists extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cards_id',
        'position'
    ];

    public function checklists()
    {
        return $this->hasMany(Checklists::class, 'title_checklists_id');
    }

    public function cards()
    {
        return $this->belongsTo(Card::class);
    }
}
