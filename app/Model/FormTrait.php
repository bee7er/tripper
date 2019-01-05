<?php

namespace App\Model;

trait FormTrait
{
    /**
     * Wraps the content in the various lightbox blocks
     *
     * @param $action
     * @param $insertAction
     * @return string
     */
    public function getFormWrapper($formTitle, $body)
    {
        // From: https://mdbootstrap.com/docs/jquery/modals/forms/
        $html = '<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold"><strong>' . $formTitle . '</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body mx-3">' .

            $body .

             '</div>
              <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn submit" onclick="submitForm()">Submit</button>
              </div>
            </div>
          </div>
        </div>';

        return $html;
    }
}
