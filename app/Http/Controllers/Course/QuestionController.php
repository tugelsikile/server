<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Repositories\Course\QuestionRepository;
use App\Validations\Course\QuestionValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected QuestionRepository $repository;
    protected QuestionValidation $validation;
    public function __construct()
    {
        $this->repository = new QuestionRepository();
        $this->validation = new QuestionValidation();
    }

    /* @
     * @param Request $request
     * @return JsonResponse
     */
    public function crud(Request $request): JsonResponse
    {
        try {
            $code = 400; $message = 'undefined'; $params = null;
            switch (strtolower($request->method())) {
                case 'post':
                    $params = $this->repository->table($request);
                    $code = 200; $message = 'ok';
                    break;
                case 'put':
                    $params = $this->repository->store($this->validation->create($request));
                    $code = 200; $message = 'pertanyaan berhasil ditambahkan';
                    break;
                case 'patch':
                    $params = $this->repository->store($this->validation->update($request));
                    $code = 200; $message = 'pertanyaan berhasil dirubah';
                    break;
                case 'delete':
                    $params = $this->repository->delete($this->validation->delete($request));
                    $code = 200; $message = 'pertanyaan berhasil dihapus';
                    break;
            }
            return responseFormat($code, $message, $params);
        } catch (\Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
}
