<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Model\Clist;
use App\Http\Requests\Admin\ClistRequest;
use App\Model\ClistConstant;
use App\Model\Constant;
use Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ClistController extends AdminController
{
    /**
     * ClistController constructor.
     */
    public function __construct()
    {
        view()->share('type', 'clist');
    }

    /**
     * Show a list of all the clist posts.
     *
     * @return View
     */
    public function index()
    {
        $clists = $this->getClists();
        // Show the page
        return view('admin.clist.index', compact('clists'));
    }

    /**
     * Show the form for creating a new object
     *
     * @return Response
     */
    public function create()
    {
        // Use an empty new object
        $clist = new Clist();
        // Show the page
        $constants = Clist::getListEntries($clist->id);

        // Show the page
        return view('admin.clist.create_edit', compact(['clist', 'constants']));
    }

    /**
     * Store a newly created object in storage
     *
     * @return Response
     */
    public function store(ClistRequest $request)
    {
        Log::info('Saving new data', [$request->get('label'), $request->get('clist')]);

        $clistObj = new Clist([
            'label' => $request->get('label')
        ]);
        $clistObj->save();
    }

    /**
     * Show the form for editing the specified object.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Clist $clist)
    {
        // Show the page
        $constants = Clist::getListEntries($clist->id);

        return view('admin.clist.create_edit', compact('clist', 'constants'));
    }

    /**
     * Update the specified object in storage
     *
     * @param  int $id
     * @return Response
     */
    public function update(ClistRequest $request, Clist $clist)
    {
        try {
            Log::info('Update clist', []);

            $clist->update([
                'label' => $request->get('label')
            ]);
        } catch (\Exception $e) {
            Log::info('Error updating data: ' . $clist->id, [
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

    public function delete(Clist $clist)
    {
        return view('admin.clist.delete', compact('clist'));
    }

    /**
     * Remove the specified object from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Clist $clist)
    {
        $clist->delete();
    }

    /**
     * Get all clists
     *
     * @return array
     */
    public function getClists()
    {
        return Clist::get()
            ->map(function ($clist) {
                return [
                    'id' => $clist->id,
                    'label' => $clist->label,
                    'created_at' => $clist->created_at->format('d/m/Y'),
                ];
            });
    }

    /**
     * Build and return the form for editing a constant
     *
     * @return Response
     */
    public function getConstantForm()
    {
        $success = true;
        $clistId = Input::get('clistId');
        $constantId = Input::get('constantId');
        $action = Input::get('action');
        $messages = [];
        $formHtml = null;
        if (!$clistId) {
            $success = false;
            $messages[] = 'Error, clist id not found in function parameters';
        } else {
            /** @var Clist $clist */
            $clist = Clist::find($clistId);

            $constant = null;
            if ($constantId) {
                $constant = Constant::find($constantId);
            }

            if (!$clist) {
                $success = false;
                $messages[] = "Error, could not find instance for id $clistId";
            } else {
                $formHtml = $clist->getEditForm($constant, $action);
            }
        }

        return Response::json(array(
            'success' => $success,
            'data'   => ['formHtml' => $formHtml, 'messages' => $messages]
        ));
    }

    /**
     * Save a clist
     *
     * @return Response
     */
    public function saveAction()
    {
        $result = [];
        try {
            $formData = $this->getFormData();
            if (!isset($formData['clistId'])) {
                throw new \Exception('Clist id not supplied for save function');
            }

            $clistConstant = new ClistConstant();

            $result = $clistConstant->insertOrUpdate($formData);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['data'] = ['messages' => [$e->getMessage() . ' For more info see log.']];

            Log::info("Error saving clist constant", [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                print_r($formData, true)
            ]);
        }

        return Response::json(array(
            'success' => $result['success'],
            'data'   => $result['data'],
        ));
    }

    /**
     * Show a list of all the clists formatted for Datatables
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $clists = $this->getClists();

        return Datatables::of($clists)
            ->add_column('actions',
                '<a href="{{{ url(\'admin/clist/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a> ' .
                '<a href="{{{ url(\'admin/clist/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a> ' .
                '<input type="hidden" name="row" value="{{$id}}" id="row">'
            )
            ->remove_column('id')
            ->make();
    }
}
