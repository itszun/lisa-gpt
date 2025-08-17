<?php
namespace App\Filament\Resources\CompanyPropertyResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CompanyProperty;

/**
 * @property CompanyProperty $resource
 */
class CompanyPropertyTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
