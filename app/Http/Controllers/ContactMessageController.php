<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

class ContactMessageController extends Controller
{
    /**
     * Store a new visitor contact message from the home page.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $msg = ContactMessage::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'status'  => 'UNREAD',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. Our team will get back to you shortly.',
                'contact' => $msg
            ]);
        });
    }

    /**
     * Get DataTables list of contact messages for Admin panel.
     */
    public function anyData()
    {
        $messages = ContactMessage::query();

        return DataTables::of($messages)
            ->editColumn('name', function ($row) {
                return e($row->name);
            })
            ->editColumn('subject', function ($row) {
                return e($row->subject);
            })
            ->editColumn('message', function ($row) {
                return e($row->message);
            })
            ->addColumn('action', function ($row) {
                $statusBtn = ($row->status === 'UNREAD')
                    ? '<button class="btn-mark-read" data-id="' . $row->id . '" style="background:#51cf66; color:#000; border:none; padding:4px 10px; border-radius:4px; font-weight:bold; cursor:pointer; margin-right:5px;"><i class="fa-solid fa-check"></i> Mark Read</button>'
                    : '<span style="color:#aaa; margin-right:8px;"><i class="fa-solid fa-check-double"></i> Read</span>';

                $deleteBtn = '<button class="btn-delete-msg" data-id="' . $row->id . '" style="background:#ff6b6b; color:#fff; border:none; padding:4px 10px; border-radius:4px; font-weight:bold; cursor:pointer;"><i class="fa-solid fa-trash"></i> Delete</button>';

                return $statusBtn . $deleteBtn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Mark a message as READ.
     */
    public function markRead(Request $request)
    {
        $request->validate(['id' => 'required|exists:contact_messages,id']);
        $msg = ContactMessage::findOrFail($request->id);
        $msg->update([
            'status'  => 'READ',
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as READ.'
        ]);
    }

    /**
     * Delete a contact message.
     */
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|exists:contact_messages,id']);
        ContactMessage::destroy($request->id);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.'
        ]);
    }

    /**
     * Get unread messages count for notification badge.
     */
    public function unreadCount()
    {
        $count = ContactMessage::where('status', 'UNREAD')->count();
        return response()->json(['unread_count' => $count]);
    }
}
