<?php

namespace BamboleeDigital\TranslatableResourceKit\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class TranslatableResource extends JsonResource
{
    public function toArray($request)
    {
        return $this->getTranslatedArray($request);
    }

    protected function getTranslatedArray($request)
    {
        $data = parent::toArray($request);

        if (method_exists($this->resource, 'translatedAttributes')) {
            $data = array_merge($data, $this->resource->translatedAttributes());
        }

        return $data;
    }
}