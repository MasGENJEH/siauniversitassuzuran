<?php

namespace App\Http\Controllers;

use App\Models\CourseClassExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseClassExamController extends Controller
{
    /**
     * Get exams for a specific class.
     */
    public function index(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
        ]);

        $exams = CourseClassExam::with('lecturer')
            ->where('course_class_id', $request->course_class_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }

    /**
     * Store or update an exam schedule for a class.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_id' => 'required|exists:course_classes,id',
            'exam_type' => 'required|in:UTS,UAS',
            'tanggal' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'lecturer_id' => 'nullable|exists:lecturers,id',
            'method' => 'required|in:online,offline',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exam = CourseClassExam::updateOrCreate(
            [
                'course_class_id' => $request->course_class_id,
                'exam_type' => $request->exam_type,
            ],
            [
                'tanggal' => $request->tanggal,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room' => $request->room,
                'lecturer_id' => $request->lecturer_id,
                'method' => $request->method,
                'notes' => $request->notes,
            ]
        );

        $exam->load('lecturer');

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal ujian berhasil disimpan.',
            'data' => $exam
        ]);
    }

    /**
     * Delete an exam schedule
     */
    public function destroy($id)
    {
        $exam = CourseClassExam::find($id);
        
        if (!$exam) {
            return response()->json(['status' => 'error', 'message' => 'Ujian tidak ditemukan'], 404);
        }

        $exam->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal ujian berhasil dihapus.'
        ]);
    }
}
