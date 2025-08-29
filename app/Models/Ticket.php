<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'subject','body','status','categor_id','note',
        'ai_provider','ai_model','ai_confidence','classified_at',
        ];
    
    protected $attributes = [
        'status' => 'open',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
