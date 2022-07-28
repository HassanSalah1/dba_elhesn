<?php
namespace App\Services\Dashboard\Contact;

use App\Repositories\Dashboard\Contact\ContactRepository;
use App\Repositories\General\UtilsRepository;
use App\Repositories\General\ValidationRepository;

class ContactService
{


    public static function getContactsData(array $data)
    {
        return ContactRepository::getContactsData($data);
    }


    public static function replayContact(array $data)
    {
        $rules = [
            'message' => 'required',
        ];
        $validated = ValidationRepository::validateWebGeneral($data, $rules);
        if ($validated !== true) {
            return $validated;
        }
        $response = ContactRepository::replayContact($data);
        return UtilsRepository::response($response, trans('admin.process_success_message')
            , '');
    }
}

?>
