<?php
namespace App\Filament\Resources\CompanyResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

/**
 * @property Company $resource
 */
class CompanyTransformer extends JsonResource
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
