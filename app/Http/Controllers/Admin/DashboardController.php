<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Language;
use App\Model\Question;
use App\Trip;
use App\User;

class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        view()->share('type', '');
    }

	public function index()
	{
        $title = "Dashboard";

        $users = User::count();
        $languages = Language::count();
        $questions = Question::count();
        $trips = Trip::count();
		return view('admin.dashboard.index',  compact('title','trips','questions','languages','users'));
	}
}