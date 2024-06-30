<?php

namespace App\Http\Resources;

use App\Services\UserService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'full_name' => $this->fio,
            'tariff' => $this->tariff->name,
            'activation_at' => $this->activate_day,
            'expiries_at' => $this->exp_date,
            'days_left' => UserService::getDaysLeft(),
            'payment_id' => $this->id + 1000000,
            'device_1' => strtoupper($this->mac),
            'device_2' => strtoupper($this->mac2),
            'device_3' => strtoupper($this->mac3),
            'online' => (bool)$this->status,
            'locked' => (bool)$this->blocking_id,
            'credit' => !($this->prepay_day_traf === 0),
            'credit_allow' => UserService::isAllowedCredit(),
            'street' => $this->street->name,
            'house' => $this->block,
            'floor' => $this->level,
            'appartment' => $this->kv,
            'session' => new SessionResource($this->session),
            //'connections' => ConnectionResource::collection($this->connections)
        ];
    }
}
