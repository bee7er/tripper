<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Language;
use App\Model\Clist;
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
        //phpinfo();

        //die('in dashoard controller');


        $title = "Dashboard";

        $users = User::count();
        $languages = Language::count();
        $trips = Trip::count();
        $questions = Question::count();
        $clists = Clist::count();
		return view('admin.dashboard.index',  compact('title','trips','questions','clists','languages','users'));
	}
}