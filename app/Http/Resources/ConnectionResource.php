<?php

namespace App\Http\Resources;

use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class ConnectionResource extends JsonResource
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
            'tx' => UserService::bytesToHuman($this->in),
            'rx' => UserService::bytesToHuman($this->out),
            'time' => CarbonInterval::seconds($this->time)->cascade()->forHumans(),
            'date' => Carbon::parse($this->date)->toDayDateTimeString(), // diffForHumans()
            'ip' => $this->ip
        ];
    }
}
