<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Trip;
use App\Http\Requests\Admin\TripRequest;
use Datatables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TripController extends AdminController
{
    /**
     * TripController constructor.
     */
    public function __construct()
    {
        view()->share('type', 'trip');
    }

    /**
     * Show a list of all the trip posts.
     *
     * @return View
     */
    public function index()
    {
        $trips = $this->getTrips();
        // Show the page
        return view('admin.trip.index', compact('trips'));
    }

    /**
     * Show the form for creating a new object
     *
     * @return Response
     */
    public function create()
    {
        // Show the page
        return view('admin.trip.create_edit', compact([]));
    }

    /**
     * Store a newly created object in storage
     *
     * @return Response
     */
    public function store(TripRequest $request)
    {
        Log::info('Saving new data', [$request->get('title')]);

        $title = $request->get('title');
        $trip = new Trip([
            'title' => $title
        ]);
        $trip->save();
    }

    /**
     * Show the form for editing the specified object.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Trip $trip)
    {
        // Show the page
        return view('admin.trip.create_edit', compact('trip'));
    }

    /**
     * Update the specified object in storage
     *
     * @param  int $id
     * @return Response
     */
    public function update(TripRequest $request, Trip $trip)
    {
        try {
            Log::info('Update trip', []);
            $title = $request->get('title');

            $trip->update([
                'title' => $title
            ]);
        } catch (\Exception $e) {
            Log::info('Error updating data: ' . $trip->id, [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }
    }

    /**
     * Remove the specified object from storage
     *
     * @param $id
     * @return Response
     */

    public function delete(Trip $trip)
    {
        return view('admin.trip.delete', compact('trip'));
    }

    /**
     * Remove the specified object from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
    }

    /**
     * Get all trips
     *
     * @return array
     */
    public function getTrips()
    {
        return Trip::get()
            ->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'title' => $trip->title,
                    'created_at' => $trip->created_at->format('d/m/Y'),
                ];
            });
    }

    /**
     * Show a list of all the trips formatted for Datatables
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $trips = $this->getTrips();

        return Datatables::of($trips)
            ->add_column('actions',
                '<a href="{{{ url(\'admin/trip/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a> ' .
                '<a href="{{{ url(\'admin/trip/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a> ' .
                '<a href="{{{ url(\'admin/actionDiagram/\' . $id . \'/editActionDiagram\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/actionDiagram.editActionDiagram") }}</a> ' .
                '<input type="hidden" name="row" value="{{$id}}" id="row">'
            )
            ->remove_column('id')
            ->make();
    }
}
