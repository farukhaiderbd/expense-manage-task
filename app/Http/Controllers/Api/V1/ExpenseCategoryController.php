<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Resources\ExpenseCategoryResource;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group ExpenseCategory
 */
class ExpenseCategoryController extends Controller
{
    public function index()
        {
        return ExpenseCategoryResource::collection(ExpenseCategory::all());
        }

    public function store(StoreExpenseCategoryRequest $request)
        {
            $validateData =$request->validated();
            if(!empty($request->file('image'))){
                $file = $request->file('image');
                $filename = uniqid() . "_" . $file->getClientOriginalName();
                $file->move(public_path('/uploads/expense_category/'), $filename);
                $imagePath = '/uploads/expense_category/' . $filename;
                $validateData['image'] = $imagePath;
            }
            $category = ExpenseCategory::create($validateData);
            return ExpenseCategoryResource::make($category);
        }

    public function show(ExpenseCategory $expenseCategory)
        {
        return ExpenseCategoryResource::make($expenseCategory);
        }

    public function update(StoreExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
        {
            $validateData =$request->validated();
            if(!empty($request->file('image'))){
                $file = $request->file('image');
                $filename = uniqid() . "_" . $file->getClientOriginalName();
                $result =$file->move(public_path('/uploads/expense_category/'), $filename);
                $imagePath = '/uploads/expense_category/' . $filename;
                $validateData['image'] = $imagePath;
                if(!empty($result)){
                    if(file_exists($expenseCategory->image)){
                        unlink($expenseCategory->image);
                    }
                }
            }
            $expenseCategory->update($validateData);
            return response()->json(ExpenseCategoryResource::make($expenseCategory), Response::HTTP_ACCEPTED);
        }

    public function destroy(ExpenseCategory $expenseCategory)
        {
        $expenseCategory->delete();
        return response()->noContent();
        }
}
