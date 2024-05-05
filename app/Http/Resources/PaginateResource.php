<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Leugin\KitchenCore\Helper\Paginate;

class PaginateResource extends ResourceCollection
{
    private $transformer;

    public function __construct(LengthAwarePaginator $resource, callable $transformer = null)
    {
        parent::__construct($resource);
        $this->transformer = $transformer;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            [
            'data' => $this->collection->transform(
                function ($item) {
                    if ($this->transformer) {
                        return call_user_func($this->transformer, $item);
                    }
                    return new BasicResource($item);
                }
            ),
            'paginate' => Paginate::meta($this->resource),
            ], empty($this->additional) ? []: [
            'extra'=>$this->additional
            ]
        );
    }
}
