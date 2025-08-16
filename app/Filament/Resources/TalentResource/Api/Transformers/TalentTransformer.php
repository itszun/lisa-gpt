<?php
namespace App\Filament\Resources\TalentResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Talent;

/**
 * @property Talent $resource
 */
class TalentTransformer extends JsonResource
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
