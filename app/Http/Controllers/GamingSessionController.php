<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\GamingSession;
use App\Models\Reservation;
use App\Models\Computer;
use App\Models\Package;
use App\Models\User;
use Yajra\Datatables\Datatables;

class GamingSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Start a session from an existing reservation.
     */
    public function startFromReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $reservation = Reservation::where('id', $request->reservation_id)->lockForUpdate()->first();

            // 1. Check if reservation already has an active or completed session
            $existingSession = GamingSession::where('reservation_id', $reservation->id)
                ->whereIn('status', ['ACTIVE', 'COMPLETED'])
                ->first();
            if ($existingSession) {
                return response()->json(['success' => false, 'message' => 'This reservation already has an active or completed session!'], 422);
            }

            // 2. Check station operational status
            $station = Computer::where('cid', $reservation->computer_id)->first();
            if (!$station) {
                return response()->json(['success' => false, 'message' => 'Reserved station does not exist.'], 422);
            }
            if (in_array($station->status, ['MAINTENANCE', 'OFFLINE'])) {
                return response()->json(['success' => false, 'message' => 'Station #' . $station->cid . ' is currently under ' . $station->status . ' and cannot start a session.'], 422);
            }

            // 3. Check if station already has an ACTIVE session
            $activeStationSession = GamingSession::where('station_id', $station->cid)
                ->where('status', 'ACTIVE')
                ->first();
            if ($activeStationSession) {
                return response()->json(['success' => false, 'message' => 'Station #' . $station->cid . ' ALREADY HAS AN ACTIVE GAMING SESSION!'], 422);
            }

            // 4. Calculate timing & price base
            $package = Package::where('package_id', $reservation->package_id)->first();
            $durationHours = $package ? $package->package_time : 1;
            $baseAmount = $package ? $package->package_price : 150;

            $now = now();
            $expectedEnd = (clone $now)->addHours($durationHours);

            // 5. Create Session
            $session = GamingSession::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'station_id' => $station->cid,
                'started_at' => $now,
                'expected_end_at' => $expectedEnd,
                'status' => 'ACTIVE',
                'duration_minutes' => $durationHours * 60,
                'base_amount' => $baseAmount,
                'notes' => 'Started from Reservation #' . $reservation->id
            ]);

            // 6. Update station operational status to PLAYING
            $station->update(['status' => 'PLAYING']);

            return response()->json([
                'success' => true,
                'message' => 'Gaming Session started successfully for Station #' . $station->cid,
                'session' => $session
            ]);
        });
    }

    /**
     * Start a walk-in gaming session.
     */
    public function startWalkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'station_id' => 'required',
            'package_id' => 'required',
            'customer_name' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $stationId = $request->station_id;
            $station = Computer::where('cid', $stationId)->first();

            if (!$station) {
                return response()->json(['success' => false, 'message' => 'Selected station does not exist.'], 422);
            }
            if (in_array($station->status, ['MAINTENANCE', 'OFFLINE'])) {
                return response()->json(['success' => false, 'message' => 'Station #' . $stationId . ' is under ' . $station->status . '!'], 422);
            }

            // Check if station has an active session
            $activeSession = GamingSession::where('station_id', $stationId)->where('status', 'ACTIVE')->first();
            if ($activeSession) {
                return response()->json(['success' => false, 'message' => 'Station #' . $stationId . ' ALREADY HAS AN ACTIVE GAMING SESSION!'], 422);
            }

            $package = Package::where('package_id', $request->package_id)->first();
            $durationHours = $package ? $package->package_time : 1;
            $baseAmount = $package ? $package->package_price : 125;

            $now = now();
            $expectedEnd = (clone $now)->addHours($durationHours);

            // Optional user matching
            $userId = null;
            if ($request->has('user_id') && !empty($request->user_id)) {
                $userId = $request->user_id;
            } elseif (Auth::check()) {
                $userId = Auth::id();
            }

            $session = GamingSession::create([
                'reservation_id' => null,
                'user_id' => $userId,
                'station_id' => $stationId,
                'started_at' => $now,
                'expected_end_at' => $expectedEnd,
                'status' => 'ACTIVE',
                'duration_minutes' => $durationHours * 60,
                'base_amount' => $baseAmount,
                'notes' => 'Walk-in Session for ' . ($request->customer_name ?? 'Guest')
            ]);

            $station->update(['status' => 'PLAYING']);

            return response()->json([
                'success' => true,
                'message' => 'Walk-in Session started on Station #' . $stationId,
                'session' => $session
            ]);
        });
    }

    /**
     * End an active gaming session.
     */
    public function endSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:gaming_sessions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $session = GamingSession::where('id', $request->session_id)->lockForUpdate()->first();

            if ($session->status !== 'ACTIVE') {
                return response()->json(['success' => false, 'message' => 'This session is not currently ACTIVE.'], 422);
            }

            $now = now();
            $startedAt = \Carbon\Carbon::parse($session->started_at);
            $actualDurationMinutes = max(1, $startedAt->diffInMinutes($now));

            $session->update([
                'ended_at' => $now,
                'status' => 'COMPLETED',
                'duration_minutes' => $actualDurationMinutes
            ]);

            // Restore station status: check if upcoming reservations exist today
            $stationId = $session->station_id;
            $hasUpcomingRes = Reservation::where('computer_id', $stationId)
                ->where('date', '>=', date('Y-m-d'))
                ->count();

            $nextStatus = ($hasUpcomingRes > 0) ? 'RESERVED' : 'AVAILABLE';

            $station = Computer::where('cid', $stationId)->first();
            if ($station && !in_array($station->status, ['MAINTENANCE', 'OFFLINE'])) {
                $station->update(['status' => $nextStatus]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Session #' . $session->id . ' completed! Total duration: ' . $actualDurationMinutes . ' mins.',
                'session' => $session
            ]);
        });
    }

    /**
     * View active gaming sessions.
     */
    public function viewActive()
    {
        $activeSessions = GamingSession::with(['user', 'computer', 'reservation'])
            ->where('status', 'ACTIVE')
            ->orderBy('started_at', 'desc')
            ->get();

        // Calculate remaining seconds for backend accuracy
        $activeSessions->transform(function ($sess) {
            $now = now();
            $expectedEnd = \Carbon\Carbon::parse($sess->expected_end_at);
            $sess->remaining_seconds = max(0, $now->diffInSeconds($expectedEnd, false));
            $sess->is_expired = ($sess->remaining_seconds <= 0);
            return $sess;
        });

        return response()->json($activeSessions);
    }

    /**
     * Yajra Datatables query for Session History.
     */
    public function anyData()
    {
        $sessions = GamingSession::with(['user', 'computer', 'reservation'])
            ->select('gaming_sessions.*');

        return Datatables::of($sessions)
            ->addColumn('customer_name', function ($sess) {
                if ($sess->user) {
                    return $sess->user->first_name . ' ' . $sess->user->last_name;
                }
                return $sess->notes ? $sess->notes : 'Walk-in Guest';
            })
            ->addColumn('station_label', function ($sess) {
                return ($sess->station_id <= 5) ? 'PS5 Lounge #' . $sess->station_id : 'PC Arena #' . $sess->station_id;
            })
            ->editColumn('started_at', function ($sess) {
                return $sess->started_at ? date('Y-m-d H:i:s', strtotime($sess->started_at)) : '--';
            })
            ->editColumn('ended_at', function ($sess) {
                return $sess->ended_at ? date('Y-m-d H:i:s', strtotime($sess->ended_at)) : '--';
            })
            ->make(true);
    }

    /**
     * View logged-in customer's session history.
     */
    public function userSessions()
    {
        $userId = Auth::id();
        $sessions = GamingSession::with(['computer'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sessions);
    }
}
