<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
/**
 * @group Statistics
 */
class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $total_expense = Expense::select('user_id','id','amount')->where('user_id',$request->user_id)->sum('amount');
        $categories = ExpenseCategory::select('id','user_id','name')->where('expense_categories.user_id',$request->user_id)->withSum('expenses','amount')->get();
        $updateCategory =[];
        if(!empty($categories)){
           foreach ($categories as $key=>$category){
               $total = $total_expense;
               $portion = $category->expenses_sum_amount;
               $percentage = ($portion / $total) * 100;
               $updateCategory[$key]['id'] = $category->id;
               $updateCategory[$key]['user_id'] = $category->user_id;
               $updateCategory[$key]['name'] = $category->name;
               $updateCategory[$key]['amount_of_total'] = $category->expenses_sum_amount;
               $updateCategory[$key]['percent_of_total'] =number_format($percentage).'%';
           }
       }
        $response = [
        'total_expense' =>$total_expense,
        'categories'=>$updateCategory
        ];

        return \Response::json($response);
    }
}
