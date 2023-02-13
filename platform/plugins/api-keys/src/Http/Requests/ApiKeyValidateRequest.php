<?php

namespace Botble\ApiKeys\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ApiKeyValidateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'api_key'   => 'required',
            'api_secret_key' => 'required',
        ];
    }
}
