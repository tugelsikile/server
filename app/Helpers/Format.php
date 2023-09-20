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
function toNum($str)
{
    $limit = 5; //apply max no. of characters
    $colLetters = strtoupper($str); //change to uppercase for easy char to integer conversion
    $strlen = strlen($colLetters); //get length of col string
    if ($strlen > $limit)    return "Column too long!"; //may catch out multibyte chars in first pass
    preg_match("/^[A-Z]+$/", $colLetters, $matches); //check valid chars
    if (!$matches) return "Invalid characters!"; //should catch any remaining multibyte chars or empty string, numbers, symbols
    $it = 0;
    $vals = 0; //just start off the vars
    for ($i = $strlen - 1; $i > -1; $i--) { //countdown - add values from righthand side
        $vals += (ord($colLetters[$i]) - 64) * pow(26, $it); //cumulate letter value
        $it++; //simple counter
    }
    return $vals; //this is the answer
}

function toStr($n, $case = 'upper')
{
    $alphabet   = array(
        'A',    'B',    'C',    'D',    'E',    'F',    'G',
        'H',    'I',    'J',    'K',    'L',    'M',    'N',
        'O',    'P',    'Q',    'R',    'S',    'T',    'U',
        'V',    'W',    'X',    'Y',    'Z'
    );
    $n             = $n;
    if ($n <= 26) {
        $alpha     =  $alphabet[$n - 1];
    } elseif ($n > 26) {
        $dividend   = ($n);
        $alpha      = '';
        $modulo;
        while ($dividend > 0) {
            $modulo     = ($dividend - 1) % 26;
            $alpha      = $alphabet[$modulo] . $alpha;
            $dividend   = floor((($dividend - $modulo) / 26));
        }
    }
    if ($case == 'lower') {
        $alpha = strtolower($alpha);
    }
    return $alpha;
}
