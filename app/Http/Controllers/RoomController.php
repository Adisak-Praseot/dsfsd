<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'room'])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'customer_name' => optional($booking->customer)->name ?? 'ไม่มีข้อมูลลูกค้า',
                    'customer_phone' => optional($booking->customer)->phone ?? 'ไม่มีเบอร์โทร',
                    'room_status' => optional($booking->room)->status ?? 'ไม่มีข้อมูลห้อง',
                    'room_number' => optional($booking->room)->room_number ?? 'ไม่มีหมายเลขห้อง',
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                ];
            });

        return inertia('Rooms/Index', ['bookings' => $bookings]);
    }

    public function create()
    {
        $rooms = Room::where('status', 'not_reserved')->get(['id', 'room_number', 'status']);
        return Inertia::render('Rooms/Create', ['rooms' => $rooms]);
    }

    public function store(Request $request)
<<<<<<< HEAD
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:10',
            'room_id' => 'required|string|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        DB::transaction(function () use ($validated) {
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['customer_phone']],
                ['name' => $validated['customer_name']]
            );

            $room = Room::findOrFail($validated['room_id']);

            // ตรวจสอบห้องว่าไม่ได้ถูกจองในวันที่เช็คอินที่เลือก
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('check_in_date', [
                        $validated['check_in_date'],
                        $validated['check_out_date']
                    ])
                    ->orWhereBetween('check_out_date', [
                        $validated['check_in_date'],
                        $validated['check_out_date']
                    ]);
                })
                ->exists();

            if ($existingBooking) {
                throw new \Exception('ห้องนี้ถูกจองแล้วในช่วงเวลาที่เลือก');
            }

            // ตรวจสอบการเว้นระยะ 1 วัน
            $existingBookingWithBuffer = Booking::where('room_id', $validated['room_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('check_in_date', [
                        date('Y-m-d', strtotime($validated['check_in_date'] . ' -1 day')),
                        date('Y-m-d', strtotime($validated['check_in_date'] . ' +1 day'))
                    ]);
                })
                ->exists();

            if ($existingBookingWithBuffer) {
                throw new \Exception('ห้องนี้มีการจองในช่วงวันก่อนหน้าและวันหลัง');
            }

            // สร้างการจอง
            Booking::create([
                'customer_id' => $customer->id,
                'room_id' => $validated['room_id'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
            ]);

            // อัปเดตสถานะห้องเป็น 'reserved'
            $room->update(['status' => 'reserved']);
        });

        return redirect()->route('rooms.index')->with('success', 'การจองสำเร็จแล้ว');
    }
=======
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:10',
        'room_id' => 'required|string|exists:rooms,id',
        'check_in_date' => 'required|date|after_or_equal:today',
        'check_out_date' => 'required|date|after:check_in_date',
    ]);

    DB::transaction(function () use ($validated) {
        $customer = Customer::firstOrCreate(
            ['phone' => $validated['customer_phone']],
            ['name' => $validated['customer_name']]
        );

        $room = Room::findOrFail($validated['room_id']);
        if ($room->status !== 'not_reserved') {
            throw new \Exception('ห้องนี้ถูกจองแล้ว');
        }

        Booking::create([
            'customer_id' => $customer->id,
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
        ]);

        $room->update(['status' => 'reserved']);
    });

    return redirect()->route('rooms.index')->with('success', 'การจองสำเร็จแล้ว');
}
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5

    public function edit($id)
    {
        $booking = Booking::with('customer', 'room')->findOrFail($id);
        $rooms = Room::where('status', 'not_reserved')
            ->orWhere('id', $booking->room_id)
            ->get(['id', 'room_number', 'status']);
<<<<<<< HEAD

=======
        
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
        return Inertia::render('Rooms/Edit', [
            'booking' => $booking,
            'rooms' => $rooms,
        ]);
    }
<<<<<<< HEAD

=======
    
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:10',
            'room_id' => 'required|string|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
<<<<<<< HEAD
        ]);
=======
        ]);        
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5

        DB::transaction(function () use ($validated, $id) {
            $booking = Booking::findOrFail($id);

<<<<<<< HEAD
            // ตรวจสอบห้องว่าไม่ได้ถูกจองในวันที่เช็คอินที่เลือก
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('check_in_date', [
                        $validated['check_in_date'],
                        $validated['check_out_date']
                    ])
                    ->orWhereBetween('check_out_date', [
                        $validated['check_in_date'],
                        $validated['check_out_date']
                    ]);
                })
                ->where('id', '!=', $id) // ไม่ให้ตรวจสอบการจองตัวเอง
                ->exists();

            if ($existingBooking) {
                throw new \Exception('ห้องนี้ถูกจองแล้วในช่วงเวลาที่เลือก');
            }

            // ตรวจสอบการเว้นระยะ 1 วัน
            $existingBookingWithBuffer = Booking::where('room_id', $validated['room_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('check_in_date', [
                        date('Y-m-d', strtotime($validated['check_in_date'] . ' -1 day')),
                        date('Y-m-d', strtotime($validated['check_in_date'] . ' +1 day'))
                    ]);
                })
                ->where('id', '!=', $id) // ไม่ให้ตรวจสอบการจองตัวเอง
                ->exists();

            if ($existingBookingWithBuffer) {
                throw new \Exception('ห้องนี้มีการจองในช่วงวันก่อนหน้าและวันหลัง');
            }

            // อัปเดตการจอง
=======
            // อัปเดตข้อมูลการจอง
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
            $booking->update([
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'room_id' => $validated['room_id'],
            ]);

            // อัปเดตข้อมูลลูกค้า
            $customer = Customer::find($booking->customer_id);
            $customer->update([
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
            ]);
<<<<<<< HEAD

            // หากห้องถูกเปลี่ยนไป ให้ปรับสถานะห้องเก่าเป็น 'not_reserved'
            if ($booking->room_id != $validated['room_id']) {
                $booking->room->update(['status' => 'not_reserved']);
                $room = Room::findOrFail($validated['room_id']);
                $room->update(['status' => 'reserved']);
            }
=======
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
        });

        return redirect()->route('rooms.index')->with('success', 'อัปเดตข้อมูลสำเร็จแล้ว');
    }

<<<<<<< HEAD
=======
    

>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::findOrFail($id);

            if ($booking->room) {
                $booking->room->update(['status' => 'not_reserved']);
            }

            $booking->delete();
        });

        return redirect()->route('rooms.index')->with('success', 'ลบการจองสำเร็จแล้ว');
    }
<<<<<<< HEAD

    public function availableRooms()
    {
        // ดึงข้อมูลห้องที่มีสถานะเป็น 'available'
        $rooms = Room::where('status', 'available')->get();

        // ส่งข้อมูลไปยัง view หรือ component ของคุณ (ใช้ Inertia หรือ Blade ตามที่ต้องการ)
        return inertia('AvailableRooms', [
            'rooms' => $rooms
        ]);
    }
=======
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
}
