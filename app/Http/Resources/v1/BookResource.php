<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($this->unserializeAuthors($request));
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        $status = array();

        if ($request->isMethod('get')) {
            $status['status_code'] = 200;
        } elseif ($request->isMethod('post')) {
            $status['status_code'] = 201;
        } elseif ($request->isMethod('patch')) {
            $status['status_code'] = 200;
            $status['message'] = "The book ".$this->name." was updated successfully";
        };

        $status['status'] = 'success';

        return $status;
    }

    /**
     * Transform authors field to array
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection
     */
    public function unserializeAuthors($request)
    {
        $modified = $this;

        if (!is_array($this->authors)) {
            $modified['authors'] = unserialize($this->authors);
        }

        return $modified;
    }
}
