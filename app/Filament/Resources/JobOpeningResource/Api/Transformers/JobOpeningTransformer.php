<?php
namespace App\Filament\Resources\JobOpeningResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\JobOpening;

/**
 * @property JobOpening $resource
 */
class JobOpeningTransformer extends JsonResource
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
