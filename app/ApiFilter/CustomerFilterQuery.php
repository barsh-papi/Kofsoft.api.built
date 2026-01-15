<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class CustomerFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'name'=> ['eq','ne'],
        'phone'=> ['eq','ne'],
        'address_id'=> ['eq','ne'],
        'restaurant_id'=> ['eq','ne'],
        'email'=> ['eq'],
    ];
   

}