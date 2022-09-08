<?php
namespace App\Repositories\Dashboard\Contact;


use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class ContactRepository
{

    // get Contacts and create datatable data.
    public static function getContactsData(array $data)
    {
        $contacts = Contact::orderBy('id', 'DESC');
        return DataTables::of($contacts)
            ->addColumn('user', function ($contact) {
                $html = '<ul>';
                if ($contact->user) {
                    $html .= '<li>' . $contact->user->name . '</li>';
                    $html .= '<li>' . $contact->user->phone . '</li>';
                    $html .= '<li>' . $contact->user->email . '</li>';
                } else {
                    $html .= '<li>' . $contact->name . '</li>';
                    $html .= '<li>' . $contact->phone . '</li>';
                    $html .= '<li>' . $contact->email . '</li>';
                }
                $html .= '</ul>';
                return $html;
            })
            ->addColumn('contact_type', function ($contact) {
                return $contact->contact_type == 1 ? trans('admin.suggest') : trans('admin.complain');
            })
            ->addColumn('actions', function ($contact) {
                $ul = '';
                if ($contact->replay === null) {
                    $ul .= '<a data-toggle="tooltip" onclick="replay(this)" title="' . trans('admin.replay_action') . '" id="' . $contact->id . '" href="#" class="on-default remove-row btn btn-success" data-bs-toggle="modal" data-bs-target=".general_modal"><i data-feather="send"></i></a>';
                }
                return $ul;
            })->make(true);
    }


    public static function replayContact(array $data)
    {
        $contact = Contact::find($data['id']);
        if ($contact) {
            $contact->update([
                'replay' => $data['message']
            ]);
            // TODO: send notification to user
            return true;
        }
        return false;
    }

}

?>
