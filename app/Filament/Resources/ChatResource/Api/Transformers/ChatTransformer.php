<?php
namespace App\Filament\Resources\ChatResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Chat;

/**
 * @property Chat $resource
 */
class ChatTransformer extends JsonResource
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
