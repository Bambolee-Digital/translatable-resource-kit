<?php

namespace BamboleeDigital\TranslatableResourceKit;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TranslatableCollection extends ResourceCollection
{
    public $collects = TranslatableResource::class;

    public function toArray($request)
    {
        return $this->collection->toArray();
    }
}