<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Language;
use App\Template;
use App\Notice;
use App\Resource;
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
        $template = Template::count();
        $resource = Resource::count();
        $notice = Notice::count();
        $trip = Trip::count();
		return view('admin.dashboard.index',  compact('title','trip','resource','notice','template','languages','users'));
	}
}