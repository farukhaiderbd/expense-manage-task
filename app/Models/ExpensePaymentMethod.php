<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpensePaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','image'];

    public function expenses(): HasMany
        {
        return $this->hasMany(Expense::class);
        }


}
