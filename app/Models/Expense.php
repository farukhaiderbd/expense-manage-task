<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id','expense_category_id','expense_payment_method_id','amount','expense_date','note','image'];

//    protected static function booted()
//        {
//        static::addGlobalScope('user', function (Builder $builder) {
//            $builder->where('user_id', auth()->id());
//        });
//        }

    public function user(): BelongsTo
        {
        return $this->belongsTo(User::class);
        }
    public function category(): BelongsTo
        {
        return $this->belongsTo(ExpenseCategory::class);
        }

    public function payment_method(): BelongsTo
        {
        return $this->hasMany(ExpensePaymentMethod::class);
        }
}
