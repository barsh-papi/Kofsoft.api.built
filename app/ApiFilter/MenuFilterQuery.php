<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class MenuFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'restaurant_id'=> ['eq','lt','ne','gt','gte','lte'],
        
    ];
   

}