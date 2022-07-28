<?php

namespace App\Http\Resources;


use Arr;
use Illuminate\Http\Resources\Json\JsonResource;


class ApiResource extends JsonResource
{

    protected array $fillable = [];
    protected array $guarded = [];

    protected bool $timestamp = false;

    public function toArray($request): array
    {
        if (!$this->timestamp) {
            $this->guarded = array_merge($this->guarded, ["created_at", "updated_at", "deleted_at"]);
        }
        if ($this->fillable) {
            return Arr::only(Arr::except(parent::toArray($request), $this->guarded), $this->fillable);
        } else {
            return Arr::except(parent::toArray($request), $this->guarded);
        }
    }

}
