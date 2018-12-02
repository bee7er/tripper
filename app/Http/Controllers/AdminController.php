<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    /**
     * Initializer.
     *
     * @return \AdminController
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Get posted form data from the request and return it in
     * a more friendly format
     */
    protected function getFormData()
    {
        $data = Input::get();

        $formData = [];
        if ($data) {
            foreach ($data as $datum) {
                $formData[$datum['name']] = $datum['value'];
            }
        }

        return $formData;
    }
}
