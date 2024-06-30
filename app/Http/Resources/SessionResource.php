<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\CarbonInterval;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        
        return [
            'ip' => $this->ip,
            'caller_id' => $this->call_id,
            'session_time' => CarbonInterval::seconds($this->ses_time)->cascade()->forHumans()
        ];
    }
}
