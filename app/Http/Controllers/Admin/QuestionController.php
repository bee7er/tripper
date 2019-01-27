<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Model\Block;
use App\Model\Instance;
use App\Model\Question;
use App\Http\Requests\Admin\QuestionRequest;
use Datatables;
use Illuminate\Support\Facades\Log;

class QuestionController extends AdminController
{
    /**
     * QuestionController constructor.
     */
    public function __construct()
    {
        view()->share('type', 'question');
    }

    /**
     * Show a list of all the question posts.
     *
     * @return View
     */
    public function index()
    {
        $questions = $this->getQuestions();
        // Show the page
        return view('admin.question.index', compact('questions'));
    }

    /**
     * Show the form for creating a new object
     *
     * @return Response
     */
    public function create()
    {
        // Use an empty new object
        $question = new Question;
        // Show the page
        return view('admin.question.create_edit', compact(['question']));
    }

    /**
     * Store a newly created object in storage
     *
     * @return Response
     */
    public function store(QuestionRequest $request)
    {
        Log::info('Saving new data', [$request->get('label'), $request->get('question')]);

        $questionObj = new Question([
            'type' => $request->get('type'),
            'label' => $request->get('label'),
            'question' => $request->get('question'),
            'required' => $request->get('required')
        ]);
        $questionObj->save();
    }

    /**
     * Show the form for editing the specified object.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Question $question)
    {
        // Show the page
        return view('admin.question.create_edit', compact('question'));
    }

    /**
     * Update the specified object in storage
     *
     * @param  int $id
     * @return Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        try {
            Log::info('Update question', []);

            $question->update([
                'type' => $request->get('type'),
                'label' => $request->get('label'),
                'question' => $request->get('question'),
                'required' => $request->get('required')
            ]);
        } catch (\Exception $e) {
            Log::info('Error updating data: ' . $question->id, [
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

    public function delete(Question $question)
    {
        return view('admin.question.delete', compact('question'));
    }

    /**
     * Remove the specified object from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
    }

    /**
     * Get all questions
     *
     * @return array
     */
    public function getQuestions()
    {
        return Question::get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'label' => $question->label,
                    'question' => $question->question,
                    'created_at' => $question->created_at->format('d/m/Y'),
                ];
            });
    }

    /**
     * Show a list of all the questions formatted for Datatables
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $questions = $this->getQuestions();

        return Datatables::of($questions)
            ->add_column('actions',
                '<a href="{{{ url(\'admin/question/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a> ' .
                '<a href="{{{ url(\'admin/question/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a> ' .
                '<input type="hidden" name="row" value="{{$id}}" id="row">'
            )
            ->remove_column('id')
            ->make();
    }
}
