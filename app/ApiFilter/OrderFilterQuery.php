<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class OrderFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'status'=> ['eq','ne'],
    ];
   

}