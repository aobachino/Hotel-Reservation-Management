<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\Type;
use App\Repositories\ImageRepository;
use App\Repositories\RoomRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class RoomController extends Controller
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->roomRepository->getRoomsDatatable($request);
            }
            return view('room.index');
        } catch (\Exception $e) {
            Log::error('Error fetching rooms: ' . $e->getMessage());
            abort(500, 'Internal Server Error');
        }
    }
    

    public function create()
    {
        try {
            $types = Type::all();
            $roomstatuses = RoomStatus::all();
            $view = view('room.create', compact('types', 'roomstatuses'))->render();
    
            return response()->json([
                'view' => $view
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating room view: ' . $e->getMessage());
            abort(500, 'Internal Server Error');
        }
    }
    

    public function store(StoreRoomRequest $request)
    {
        try {
            $room = Room::create($request->all());
    
            Log::info('Room ' . $room->number . ' created by ' . auth()->user()->name);
    
            return response()->json([
                'message' => 'Room ' . $room->number . ' created'
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage());
            abort(500, 'Internal Server Error');
        }
    }
    

    public function show(Room $room)
    {
        try {
            $customer = [];
            $transaction = Transaction::where([
                ['check_in', '<=', Carbon::now()],
                ['check_out', '>=', Carbon::now()],
                ['room_id', $room->id]
            ])->first();
    
            if (!empty($transaction)) {
                $customer = $transaction->customer;
            }
    
            return view('room.show', compact('customer', 'room'));
        } catch (\Exception $e) {
            Log::error('Error fetching room details: ' . $e->getMessage());
            abort(500, 'Internal Server Error');
        }
    }
    

    public function edit(Room $room)
    {
        $types = Type::all();
        $roomstatuses = RoomStatus::all();
        $view = view('room.edit', compact('room', 'types', 'roomstatuses'))->render();

        return response()->json([
            'view' => $view
        ]);
    }

    public function update(Room $room, StoreRoomRequest $request)
    {
        $room->update($request->all());

        return response()->json([
            'message' => 'Room ' . $room->number . ' udpated!'
        ]);
    }

    public function destroy(Room $room, ImageRepository $imageRepository)
    {
        try {
            $roomNumber = $room->number;
            $room->delete();
    
            $path = 'img/room/' . $roomNumber;
            $path = public_path($path);
    
            if (is_dir($path)) {
                $imageRepository->destroy($path);
            }
    
            Log::info('Room number ' . $roomNumber . ' deleted by ' . auth()->user()->name);
    
            return response()->json([
                'message' => 'Room number ' . $roomNumber . ' deleted!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting room: ' . $e->getMessage());
            abort(500, 'Internal Server Error');
        }
    }
    
}
