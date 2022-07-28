<?php
namespace App\Repositories\Dashboard\Setting;


use App\Models\Faq;
use Yajra\DataTables\Facades\DataTables;

class FaqRepository
{

    // get Faqs and create datatable data.
    public static function getFaqsData(array $data)
    {
        $faqs = Faq::orderBy('id' , 'DESC');
        return DataTables::of($faqs)
            ->addColumn('actions', function ($faq) {
                $ul = '<a data-toggle="tooltip" title="' . trans('admin.edit') . '" id="' . $faq->id . '" onclick="editFaq(this);return false;" href="#" class="on-default edit-row btn btn-info"><i data-feather="edit"></i></a>
                   ';
                $ul .= '<a data-toggle="tooltip" title="' . trans('admin.delete_action') . '" id="' . $faq->id . '" onclick="deleteFaq(this);return false;" href="#" class="on-default remove-row btn btn-danger"><i data-feather="delete"></i></a>';
                return $ul;
            })->make(true);
    }

    public static function addFaq(array $data)
    {
        $faqData = [
            'question_ar' => $data['question_ar'],
            'question_en' => $data['question_en'],
            'answer_ar' => $data['answer_ar'],
            'answer_en' => $data['answer_en'],
        ];

        $created = Faq::create($faqData);
        if ($created) {
            return true;
        }
        return false;
    }

    public static function deleteFaq(array $data)
    {
        $faq = Faq::where(['id' => $data['id']])->first();
        if ($faq) {
            $faq->forceDelete();
            return true;
        }
        return false;
    }

    public static function getFaqData(array $data)
    {
        $faq = Faq::where(['id' => $data['id']])->first();
        if ($faq) {
            return $faq;
        }
        return false;
    }

    public static function editFaq(array $data)
    {
        $faq = Faq::where(['id' => $data['id']])->first();
        if ($faq) {
            $faqData = [
                'question_ar' => $data['question_ar'],
                'question_en' => $data['question_en'],
                'answer_ar' => $data['answer_ar'],
                'answer_en' => $data['answer_en'],
            ];
            $updated = $faq->update($faqData);
            if ($updated) {
                return true;
            }
        }
        return false;
    }

}

?>
