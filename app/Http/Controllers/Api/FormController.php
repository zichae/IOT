<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Libraries\ResponseBase;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FormsRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index(FormsRequest $request)
    {
        $pageNumber = $request->input('page', 1);
        $dataAmount = $request->input('limit', 10);
        $search = $request->input('search', null);

        $forms = Form::search($search)
                ->paginate($dataAmount, ['*'], 'page', $pageNumber);

        return ResponseBase::success("Berhasil menerima data form", $forms);
    }

    public function store(FormsRequest $request)
    {
        try {
            $form = new Form();
            $form->email = $request->email;
            $form->type = $request->type;
            $form->body = $request->email;
            $form->save();

            return ResponseBase::success("Berhasil menambahkan data form!", $form);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan data form -> ' . $e->getFile() . ':' . $e->getLine() . ' => ' . $e->getMessage());
            return ResponseBase::error('Gagal menambahkan data form!', 409);
        }
    }

    public function show($id)
    {
        $form = Form::findOrFail($id);

        return ResponseBase::success("Berhasil menerima data form", $form);
    }
}
