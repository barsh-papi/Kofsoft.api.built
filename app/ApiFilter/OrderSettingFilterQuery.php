<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class OrderSettingFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'name'=> ['eq','ne'],
        'role'=> ['eq','ne'],
        'email'=> ['eq'],
        'restaurant_id'=>['eq']
    ];
   

}