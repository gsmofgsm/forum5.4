<?php

namespace App\Filters;


use App\User;
use Illuminate\Http\Request;

class ThreadFilters
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        // We apply our filters to the builder
        if($this->request->has('by')){
            $this->by($this->request->by);
        }
        
        return $builder;
    }

    /**
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
}