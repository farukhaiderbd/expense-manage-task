<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
        'id' => $this->id,
        'user_id' => $this->user_id,
        'expense_category_id' => $this->expense_category_id,
        'expense_payment_method_id' =>$this->idexpense_payment_method_id,
        'amount'                                => $this->amount,
        'expense_date'                          => $this->expense_date,
        'note'                                  => $this->note,
        'image'=> $this->image
    ];
    }
}
