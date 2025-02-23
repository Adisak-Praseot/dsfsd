<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    // กำหนดว่า factory นี้จะใช้กับโมเดล Room
    protected $model = Room::class;

    /**
     * กำหนดค่าของแต่ละฟิลด์ในตาราง rooms สำหรับการทดสอบ
     */
    public function definition()
    {
        return [
            // การสุ่ม room_type_id จากประเภทห้องที่มีอยู่ หรือสร้าง room_type ใหม่หากไม่มี
            'room_type_id' => RoomType::inRandomOrder()->first()->id ?? RoomType::factory(),
            // กำหนดหมายเลขห้องให้อยู่ในช่วง A1 - A10 และ B1 - B10 เท่านั้น
            'room_number' => $this->faker->randomElement([
<<<<<<< HEAD
                'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8',
=======
                'A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10',
                'B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10'
>>>>>>> 1b2b43d9c610656a4dc770bb2016fbb0f80417f5
            ]),
            // การสุ่มสถานะของห้อง (สามารถเป็น not_reserved หรือ reserved)
            'status' => $this->faker->randomElement(['not_reserved', 'reserved']),
        ];
    }
}
