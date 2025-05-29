<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','sort_order','is_active'];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeActive($q) { return $q->where('is_active',true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order')->orderBy('name'); }
}
