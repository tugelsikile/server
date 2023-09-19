<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Repositories\Exam\ExamRepository;
use App\Validations\Exam\ExamValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected ExamRepository $repository;
    protected ExamValidation $validation;
    public function __construct()
    {
        $this->validation = new ExamValidation();
        $this->repository = new ExamRepository();
    }

    /* @
     * @param Request $request
     * @return JsonResponse
     */
    public function crud(Request $request): JsonResponse
    {
        try {
            $code = 400; $message = 'undefined method'; $params = null;
            switch (strtolower($request->method())) {
                case 'post':
                    $params = $this->repository->table($request);
                    $code = 200; $message = 'ok';
                    break;
                case 'put':
                    $params = $this->repository->store($this->validation->create($request));
                    $code = 200; $message = 'ujian berhasil ditambahkan';
                    break;
                case 'patch':
                    $params = $this->repository->store($this->validation->update($request));
                    $code = 200; $message = 'ujian berhasil dirubah';
                    break;
                case 'delete':
                    $params = $this->repository->delete($this->validation->delete($request));
                    $code = 200; $message = 'ujian berhasil dihapus';
                    break;
            }
            return responseFormat($code, $message, $params);
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
}
