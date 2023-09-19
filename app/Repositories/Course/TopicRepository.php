<?php

namespace App\Repositories\Course;

use App\Models\Course\CourseTopic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class TopicRepository
{
    /* @
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function delete(Request $request): bool
    {
        try {
            CourseTopic::where('id', $request['data_pembahasan'])->delete();
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
            if ($request->has('data_pembahasan')) {
                $topic = CourseTopic::where('id', $request['data_pembahasan'])->first();
            } else {
                $topic = new CourseTopic();
                $topic->id = Uuid::uuid4()->toString();
                $topic->code = generateCourseTopicCode();
            }
            $topic->course = $request['mata_pelajaran'];
            $topic->name = $request['judul_pembahasan'];
            $topic->save();
            return $this->table(new Request(['id' => $topic->id]))->first();
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
            $topics = CourseTopic::orderBy('name', 'asc');
            if ($request->has('id')) $topics = $topics->where('id', $request['id']);
            if ($request->has('course')) $topics = $topics->where('course', $request['course']);
            $topics = $topics->get();
            foreach ($topics as $topic) {
                $response->push((object) [
                    'value' => $topic->id,
                    'label' => $topic->name,
                    'meta' => (object) [
                        'code' => $topic->code,
                        'course' => (new CourseRepository())->table(new Request(['id' => $topic->course]))->first(),
                    ]
                ]);
            }
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}
