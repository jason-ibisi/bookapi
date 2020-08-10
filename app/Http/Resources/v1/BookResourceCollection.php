<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($this->unserializeAuthors($request));
    }

    /**
     * Transform authors field in the collection to array
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection
     */
    private function unserializeAuthors($request)
    {
        $modified = parent::map(function ($item, $key) {
            $item['authors'] = unserialize($item->authors);
        });

        return $modified;
    }
}
