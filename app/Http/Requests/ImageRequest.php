<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  Request  $request
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($request->method) {
            case Request::METHOD_GET:
                return [
                    'path' => 'required|string',
                ];
            default:
                return [
                    'image' => 'required|file|mimes:jpeg,jpg,png|max:2000',
                ];
        }
    }
}
