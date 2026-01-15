<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class MenuItemAddonFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'name'=> ['eq','ne'],
        'phone'=> ['eq','ne'],
        'role'=> ['eq','ne'],
        'email'=> ['eq'],
    ];
   

}