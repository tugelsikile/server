<?php

use App\Models\Course\CourseTopic;
use App\Models\Exam\ExamClient;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

function responseFormat(int $code, string $message = 'ok', $params = null): JsonResponse
{
    if ($code < 200 || $code > 500) $code = 400;
    return response()->json([
        'status_code' => $code,
        'status_message' => $message,
        'status_data' => $params,
    ], $code);
}
function generateExamClientToken(): string {
    return Str::upper(Str::random(5) . '-' . Str::random(5) . '-' . Str::random(3));
}
function generateExamClientCode(): string
{
    $total = ExamClient::all()->count();
    $total = $total + 1;
    $kode = Carbon::now()->format('ym') . Str::padLeft($total,3,'0');
    while (ExamClient::where('code', $kode)->count() > 0) {
        $total = $total + 1;
        $kode = Carbon::now()->format('ym') . Str::padLeft($total,3,'0');
    }
    return $kode;
}
function generateCourseCode(string $courseName): string
{
    $code = "";
    $courseName = explode(" ",$courseName);
    foreach ($courseName as $item) {
        $code .= Str::substr($item,0,1);
    }
    return $code;
}
function generateCourseTopicCode(): string
{
    $total = CourseTopic::all()->count();
    $total = $total + 1;
    $code = Carbon::now()->format('ymd') . Str::padLeft($total,3,'0');
    while (CourseTopic::where('code', $code)->count() > 0) {
        $total = $total + 1;
        $code = Carbon::now()->format('ymd') . Str::padLeft($total,3,'0');
    }
    return $code;
}
