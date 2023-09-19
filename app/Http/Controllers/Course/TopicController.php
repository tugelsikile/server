<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Repositories\Course\TopicRepository;
use App\Validations\Course\TopicValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected TopicRepository $repository;
    protected TopicValidation $validation;
    public function __construct()
    {
        $this->repository = new TopicRepository();
        $this->validation = new TopicValidation();
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
                    $code = 200; $message = 'Pembahasan berhasil dibuat';
                    break;
                case 'patch':
                    $params = $this->repository->store($this->validation->update($request));
                    $code = 200; $message = 'Pembahasan berhasil diubah';
                    break;
                case 'delete':
                    $params = $this->repository->delete($this->validation->delete($request));
                    $code = 200; $message = 'Pembahasan berhasil dihapus';
                    break;
            }
            return responseFormat($code, $message, $params);
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
}
