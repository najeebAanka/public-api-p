<?php

namespace App\CPU;

use App\Model\Category;
use App\Model\Product;

class CategoryManager
{
    public static function parents()
    {
        $x = Category::with(['childes.childes'])->where('position', 0)->get();
        return $x;
    }

    public static function child($parent_id)
    {
        $x = Category::where(['parent_id' => $parent_id])->get();
        return $x;
    }

    public static function products($category_id)
    {
        return Product::active()
            /*->where('category_ids', 'like', "%{$data['id']}%")*/
            ->whereJsonContains('category_ids', ["id" => (string)$category_id])->get();
    }
}
