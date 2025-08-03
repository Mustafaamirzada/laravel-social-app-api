<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;
    protected $policyClass;

    public function include(string $relationship) {
        $params = request()->get('include');

        if (!isset($params)) {
            return false;
        }

        $includeValues = explode(',', strtolower($params));
        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble($ability, $tagetModel) {
        return Gate::authorize($ability, [$tagetModel, $this->policyClass]);
    }
}
