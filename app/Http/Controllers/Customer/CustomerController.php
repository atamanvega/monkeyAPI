<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedVerifiedUserAction();
        $customers = Customer::all();
        return $this->showAll($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->allowedVerifiedUserAction();
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'id' =>'required|unique:customers',
            'photo' => 'image',
        ];

        $this->validate($request, $rules);
        $data = $request->all();
        $data['created_by_user_id'] = Auth::user()->id;
        $data['photo'] = $request->photo->store('');

        $customer = Customer::create($data);
        return $this->showOne($customer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $this->allowedVerifiedUserAction();
        return $this->showOne($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->allowedVerifiedUserAction();
        $rules = [
            'id' =>'unique:customers,id,' . $customer->customer_id,
            'photo' => 'image',
        ];
        $this->validate($request, $rules);
        $customer->fill($request->only([
            'name',
            'surname',
            'id',
        ]));
        if ($request->hasFile('photo')) {
            Storage::delete($customer->photo);
            $customer->photo = $request->photo->store('');
        }
        if (!$customer->isDirty()) {
            return $this->errorResponse('Set at least a different value to update the customer', 422);
        }
        $customer->modified_by_user_id = Auth::user()->id;
        $customer->save();
        return $this->showOne($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $this->allowedVerifiedUserAction();
        $customer->delete();
        Storage::delete($customer->photo);
        return $this->showOne($customer);
    }
}
