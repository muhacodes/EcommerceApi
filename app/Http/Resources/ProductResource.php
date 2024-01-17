<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductVariationResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'photo' => $this->photo ? url('storage/' . $this->photo) : null,
            'description' => $this->description,
            // Add more attributes as needed
            'variations' => ProductVariationResource::collection($this->variations),
        ];
    }
}
