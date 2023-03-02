<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Expense
 */
class ExpenseController extends Controller
{
    public function index(Request $request)
        {
        $queryExpenses = Expense::query();

        //Add sorting
        if(!empty($request['order_by']) && !empty($request['order_dir']) && in_array($request['order_dir'],array('asc','desc','ASC','DESC'))) {
           $order_dir = $request['order_dir'];
           $order_by = $request['order_by'];
           if($order_by == 'created_at'){
               $queryExpenses->orderBy('created_at',$order_dir);
           }
            if($order_by == 'amount'){
                $queryExpenses->orderBy('amount',$order_dir);
            }
        }else{
            $queryExpenses->orderBy('created_at','asc');
        }
        //Add Conditions

        if(!empty($request['category_id'])) {
            $queryExpenses->where('expense_category_id',$request['category_id']);
        }
        //Fetch list of results
        if(!empty($request['offset'] && !empty($request['per_page']))){
            $result = $queryExpenses->offset($request['offset'])->limit($request['per_page'])->get();
        }else{
            $result = $queryExpenses->get();
        }

        return ExpenseResource::collection($result);
        }

    public function store(StoreExpenseRequest $request)
        {
            $validateData =$request->validated();
            if(!empty($request->file('image'))){
                $file = $request->file('image');
                $filename = uniqid() . "_" . $file->getClientOriginalName();
                $file->move(public_path('/uploads/expense/'), $filename);
                $imagePath = '/uploads/expense/' . $filename;
                $validateData['image'] = $imagePath;
            }
            $expense = Expense::create($validateData);
            return ExpenseResource::make($expense);
        }

    public function show(Expense $expense)
        {
            return ExpenseResource::make($expense);
        }

    public function update(StoreExpenseRequest $request, Expense $expense)
        {
        $validateData =$request->validated();
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $result =$file->move(public_path('/uploads/expense_category/'), $filename);
            $imagePath = '/uploads/expense_category/' . $filename;
            $validateData['image'] = $imagePath;
            if(!empty($result)){
                if(file_exists($expense->image)){
                    unlink($expense->image);
                }
            }
        }
        $expense->update($validateData);
        return response()->json(ExpenseResource::make($expense), Response::HTTP_ACCEPTED);
        }

    public function destroy(Expense $expense)
        {
        $expense->delete();
        return response()->noContent();
        }
}
