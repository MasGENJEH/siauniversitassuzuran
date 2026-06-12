<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\CourseClassMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    /**
     * Get attendance records for a specific class.
     */
    public function index(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
        ]);

        $meetings = CourseClassMeeting::with('absensis')
            ->where('course_class_id', $request->course_class_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $meetings
        ]);
    }

    /**
     * Store or update (upsert) an attendance record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_meeting_id' => 'required|exists:course_class_meetings,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:hadir,sakit,izin,alfa',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $absensi = Absensi::updateOrCreate(
            [
                'course_class_meeting_id' => $request->course_class_meeting_id,
                'student_id' => $request->student_id,
            ],
            [
                'status' => $request->status,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil disimpan.',
            'data' => $absensi
        ]);
    }

    /**
     * Activate a meeting
     */
    public function activateMeeting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_meeting_id' => 'required|exists:course_class_meetings,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $meeting = CourseClassMeeting::find($request->course_class_meeting_id);
        $meeting->is_active = true;
        $meeting->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pertemuan berhasil diaktifkan.',
            'data' => $meeting
        ]);
    }
}
