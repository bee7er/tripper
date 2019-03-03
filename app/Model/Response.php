<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Response extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get all the responses
     *
     * @return array
     */
    public static function getResponses()
    {
        return self::select()
            ->orderBy("responses.response")
            ->get();
    }
    
    /**
     * Builds and returns a select listbox of response
     *
     * @param $selectedResponse
     * @return string
     */
    public static function getResponsesList($selectedResponse = null)
    {
        $html = '<select name="response" id="response" title="Select response">';
        $responses = self::getResponses();
        if (is_array($responses) && count($responses) > 0) {
            foreach ($responses as $response) {
                $selected = ($response == $selectedResponse ? 'selected' : '');
                $html .= "<option value='{$response->id}' $selected>" . strtoupper($response->response) . "</option>";
            }
        } else {
            $html .= "<option value='0'>No responses found</option>";
        }
        $html .= "</select>";

        return $html;
    }
}
