<?php

namespace App\Models\Gujarati;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'gujarati_categories';

    protected $fillable = [
        'name',
        'slug',
        'post_type',
        'description',
        'sort_number',
        'parent_id',
    ];

    /**
     * Get the parent category of this category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories of this category.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    // Get all children recursively
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
}

