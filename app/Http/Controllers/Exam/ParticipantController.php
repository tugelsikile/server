<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Repositories\Exam\ParticipantRepository;
use App\Validations\Exam\ParticipantValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    protected ParticipantRepository $repository;
    protected ParticipantValidation $validation;
    public function __construct()
    {
        $this->repository = new ParticipantRepository();
        $this->validation = new ParticipantValidation();
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
                    $code = 200; $message = 'Peserta berhasil ditambahkan';
                    break;
                case 'patch':
                    $params = $this->repository->store($this->validation->update($request));
                    $code = 200; $message = 'Peserta berhasil dirubah';
                    break;
                case 'delete':
                    $params = $this->repository->delete($this->validation->delete($request));
                    $code = 200; $message = 'Peserta berhasil dihapus';
                    break;
            }
            return responseFormat($code, $message, $params);
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
}
