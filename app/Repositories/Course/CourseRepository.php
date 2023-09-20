<?php

namespace App\Repositories\Course;

use App\Models\Course\Course;
use App\Repositories\Major\MajorRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class CourseRepository
{
    /* @
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function delete(Request $request): bool
    {
        try {
            Course::where('id', $request['data_mata_pelajaran'])->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
    /* @
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function store(Request $request) {
        try {
            if ($request->has('data_mata_pelajaran')) {
                $course = Course::where('id', $request['data_mata_pelajaran'])->first();
            } else {
                $course = new Course();
                $course->id = Uuid::uuid4()->toString();
            }
            $course->name = $request['nama_mata_pelajaran'];
            if ($request->has('singkatan')) {
                $course->code = $request['singkatan'];
            } else {
                $course->code = generateCourseCode($course->name);
            }
            $course->major = null;
            if ($request->has('jurusan')) $course->major = $request['jurusan'];
            $course->level = $request['tingkat'];
            $course->save();
            return $this->table(new Request(['id' => $course->id]))->first();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
    /* @
     * @param Request $request
     * @return Collection
     * @throws Exception
     */
    public function table(Request $request): Collection
    {
        try {
            $response = collect();
            $courses = Course::orderBy('name', 'asc');
            if ($request->has('id')) $courses = $courses->where('id', $request['id']);
            if ($request->has('major')) $courses = $courses->where('major', $request['major']);
            if ($request->has('level')) $courses = $courses->where('level', $request['level']);
            $courses = $courses->get(['id','major','level','name','code','level']);
            foreach ($courses as $course) {
                $major = null;
                if ($course->major != null) $major = (new MajorRepository())->table(new Request(['id' => $course->major]))->first();
                $response->push((object) [
                    'value' => $course->id,
                    'label' => $course->name,
                    'meta' => (object) [
                        'code' => $course->code,
                        'major' => $major,
                        'level' => $course->level,
                    ]
                ]);
            }
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}
