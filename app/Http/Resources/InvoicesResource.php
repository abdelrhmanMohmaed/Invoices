<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'product' => $this->product,
            'section_id' => $this->section_id,
            'amount_collection' => $this->amount_collection,
            'amount_commission' => $this->amount_commission,
            'discount' => $this->discount,
            'rate_vat' => $this->rate_vat,
            'value_vat' => $this->value_vat,
            'total' => $this->total,
            'status' => $this->status,
            'value_status' => $this->value_status,
            'note' => $this->note,
            'payment_date' => $this->payment_date,
            'user' => $this->user,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
