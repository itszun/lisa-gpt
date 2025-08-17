<?php
namespace App\Filament\Resources\CandidateResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Candidate;

/**
 * @property Candidate $resource
 */
class CandidateTransformer extends JsonResource
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
