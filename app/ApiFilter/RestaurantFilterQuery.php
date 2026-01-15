<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class RestaurantFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'name'=> ['eq','ne'],
        'user_id'=> ['eq','lt','ne','gt','gte','lte'],
        'address_id'=> ['eq','lt','ne','gt','gte','lte'],
        'template_id'=> ['eq','lt','ne','gt','gte','lte'],
        'role'=> ['eq','ne'],
        'logo'=> ['eq','ne'],
        'phone'=> ['eq','ne'],
        'email'=> ['eq'],
    ];
   

}