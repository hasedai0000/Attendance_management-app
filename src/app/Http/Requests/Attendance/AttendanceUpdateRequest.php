<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceUpdateRequest extends FormRequest
{
 /**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
 public function authorize()
 {
  return true;
 }

 /**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
 public function rules()
 {
  return [
   'clock_in' => 'nullable|date_format:H:i',
   'clock_out' => 'nullable|date_format:H:i',
   'note' => 'required|string',
   'breaks.*.start' => 'nullable|date_format:H:i',
   'breaks.*.end' => 'nullable|date_format:H:i',
  ];
 }

 /**
  * Configure the validator instance.
  *
  * @param  \Illuminate\Validation\Validator  $validator
  * @return void
  */
 public function withValidator($validator)
 {
  $validator->after(function ($validator) {
   $this->validateTimeLogic($validator);
  });
 }

 /**
  * 時間の論理的な妥当性をチェック
  *
  * @param  \Illuminate\Validation\Validator  $validator
  * @return void
  */
 protected function validateTimeLogic($validator)
 {
  $clockIn = $this->input('clock_in');
  $clockOut = $this->input('clock_out');

  // 出勤時間と退勤時間の妥当性チェック
  if ($clockIn && $clockOut) {
   if (Carbon::parse($clockIn)->gt(Carbon::parse($clockOut))) {
    $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
   }
  }

  // 休憩時間の妥当性チェック
  $breaks = $this->input('breaks', []);
  foreach ($breaks as $break) {
   if ($break['start'] && $break['end']) {
    $breakStart = Carbon::parse($break['start']);
    $breakEnd = Carbon::parse($break['end']);

    // 休憩開始時間が出勤時間より前の場合
    if ($clockIn && $breakStart->lt(Carbon::parse($clockIn))) {
     $validator->errors()->add('breaks', '休憩時間が不適切な値です');
    }

    // 休憩開始時間が退勤時間より後の場合
    if ($clockOut && $breakStart->gt(Carbon::parse($clockOut))) {
     $validator->errors()->add('breaks', '休憩時間が不適切な値です');
    }

    // 休憩終了時間が退勤時間より後の場合
    if ($clockOut && $breakEnd->gt(Carbon::parse($clockOut))) {
     $validator->errors()->add('breaks', '休憩時間もしくは退勤時間が不適切な値です');
    }

    // 休憩開始時間が休憩終了時間より後の場合
    if ($breakStart->gt($breakEnd)) {
     $validator->errors()->add('breaks', '休憩時間が不適切な値です');
    }
   }
  }
 }

 /**
  * Get custom messages for validator errors.
  *
  * @return array
  */
 public function messages()
 {
  return [
   'note.required' => '備考を記入してください',
   'clock_in.date_format' => '出勤時刻は正しい形式で入力してください',
   'clock_out.date_format' => '退勤時刻は正しい形式で入力してください',
   'breaks.*.start.date_format' => '休憩開始時刻は正しい形式で入力してください',
   'breaks.*.end.date_format' => '休憩終了時刻は正しい形式で入力してください',
  ];
 }
}
