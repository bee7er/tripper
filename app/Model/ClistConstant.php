<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ClistConstant
 * @package App\Model
 */
class ClistConstant extends Model
{
    const CC_ACTION_ADD_CONSTANT = 'addConstant';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * <pre/>Array
    (
    [clistId] => 3
    [action] => addConstant
    [label] => big
    [value] => b
    )
     */

    /**
     * Save a clist constant, with update or insert
     *
     * @param $formData
     * @return array
     */
    public function insertOrUpdate($formData)
    {
        $success = null;
        if (self::CC_ACTION_ADD_CONSTANT === $formData['action']) {
            return $this->insert($formData);
        }

        return [
            'success' => false,
            'data'   => 'Unknown action',
        ];
    }

    /**
     * Insert a new clist constant
     *
     * @param $formData
     * @return array
     */
    public function insert($formData)
    {
        $success = null;
        $messages = [];
        try {
            $constant = new Constant();
            $constant->id = null;
            $constant->label = $formData['label'];
            $constant->value = $formData['value'];
            $constant->save();

            $clistConstant = new self();
            $clistConstant->id = null;
            $clistConstant->clist_id = $formData['clistId'];
            $clistConstant->constant_id = $constant->id;
            $clistConstant->save();

            $messages[] = "Inserted '{$constant->label}'";
            $success = true;
        } catch (\Exception $e) {
            $messages[]  = $e->getMessage() . ' For more info see log.';
            $success = false;
            Log::info('Error inserting data: ' . $formData['label'], [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }

        return [
            'success' => $success,
            'data'   => ['messages' => $messages]
        ];
    }
}
