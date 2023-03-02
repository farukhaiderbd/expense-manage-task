<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpensePaymentMethodRequest;
use App\Http\Resources\ExpensePaymentMethodResource;
use App\Models\ExpensePaymentMethod;
use Illuminate\Http\Response;

/**
 * @group ExpensePaymentMethod
 */
class ExpensePaymentMethodController extends Controller
{
    public function index()
        {
        return ExpensePaymentMethodResource::collection(ExpensePaymentMethod::all());
        }

    public function store(StoreExpensePaymentMethodRequest $request)
        {
            $validateData =$request->validated();
            if(!empty($request->file('image'))){
                $file = $request->file('image');
                $filename = time()."_".uniqid(). "." . $file->getClientOriginalExtension();
                $result =$file->move(public_path('/uploads/payment_method/'), $filename);
                $imagePath = '/uploads/payment_method/' . $filename;
                $validateData['image'] = $imagePath;
            }
        $paymentMethod = ExpensePaymentMethod::create($validateData);

        return ExpensePaymentMethodResource::make($paymentMethod);
        }

    public function show(ExpensePaymentMethod $expensePaymentMethod)
        {
        return ExpensePaymentMethodResource::make($expensePaymentMethod);
        }

    public function update(StoreExpensePaymentMethodRequest $request, ExpensePaymentMethod $expensePaymentMethod)
        {
        $validateData =$request->validated();
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $filename = time()."_".uniqid(). "." . $file->getClientOriginalExtension();
            $result =$file->move(public_path('/uploads/payment_method/'), $filename);
            $imagePath = '/uploads/payment_method/' . $filename;
            $validateData['image'] = $imagePath;
            if(!empty($result)){
                if(file_exists($expensePaymentMethod->image)){
                    unlink($expensePaymentMethod->image);
                }
            }
        }
        $expensePaymentMethod->update($validateData);

        return response()->json(ExpensePaymentMethodResource::make($expensePaymentMethod), Response::HTTP_ACCEPTED);
        }

    public function destroy(ExpensePaymentMethod $expensePaymentMethod)
        {
        $expensePaymentMethod->delete();
        return response()->noContent();
        }
}
