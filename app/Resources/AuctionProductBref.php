<?php

namespace App\Resources;

use App\CPU\ProductManager;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionProductBref extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {


        $is_favourite = false;
        if (auth('api')->check()) {
            \App\Model\Wishlist::where([
                'product_id' => $this->id,
                'customer_id' => auth('api')->user()->id,
            ])->first() ? true : false;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'details' => strip_tags($this->details),
            'thumbnail' => asset('storage/product/thumbnail') . '/' . $this->thumbnail,
            'sold' => 330,
            'lowest_ask' => "1500",//without currency
            'is_favourite' => $is_favourite,
            'in_stock' => $this->current_stock > 0 ? true : false
        ];
    }
}