<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerFieldRequest;
use App\Http\Requests\UpdateCustomerFieldRequest;
use App\Models\CustomerField;
use Illuminate\Http\Request;
use App\ApiFilter\CustomerFieldFilterQuery;
use App\Http\Resources\CustomerFieldResource;
use App\Http\Resources\CustomerFieldCollection;

class CustomerFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CustomerFieldFilterQuery();
        $filterItems = $filter->transform($request);//column-operator-value
        $customerField = CustomerField::where($filterItems);
        return new CustomerFieldCollection($customerField->paginate()->appends($request->query()));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerFieldRequest $request)
    {
        return new CustomerFieldResource(CustomerField::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerField $customerField)

    { 
        return new CustomerFieldResource($customerField);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerField $customerField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerFieldRequest $request, CustomerField $customerField)
    {
        $customerField->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerField $customerField)
    {
        $customerField->delete();
        //
    }
}
