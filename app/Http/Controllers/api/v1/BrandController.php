<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\BackEndHelper;
use App\CPU\BrandManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Resources\Brand;
use App\Resources\ProductBref;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function get_brands()
    {
        try {
            $brands = BrandManager::get_brands();
        } catch (\Exception $e) {
        }

        return BackEndHelper::sendSuccess('Data Got!', Brand::collection($brands));
    }

    public function get_products($brand_id)
    {
        try {
            $products = Product::where(['brand_id' => $brand_id])->get();
        } catch (\Exception $e) {
            return BackEndHelper::sendError(['message' => $e->getMessage()], 403);
        }

        return BackEndHelper::sendSuccess('Data Got!', ProductBref::collection($products));
    }
}
