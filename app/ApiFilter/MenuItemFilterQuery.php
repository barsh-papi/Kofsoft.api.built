<?php

namespace App\ApiFilter;

use App\ApiFilter\FilterQuery;
use Illuminate\Http\Request;

class MenuItemFilterQuery extends FilterQuery{

    protected $params =[
        'id'=> ['eq','lt','ne','gt','gte','lte'],
        'menu_id'=> ['eq','lt','ne','gt','gte','lte'],
        
    ];
   

}