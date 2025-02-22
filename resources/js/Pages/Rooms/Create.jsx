import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Swal from 'sweetalert2';
import { QRCodeCanvas } from 'qrcode.react'; // ✅ นำเข้า QRCodeCanvas

export default function Create({ rooms }) {
  const { data, setData, post, errors } = useForm({
    customer_name: '',
    customer_phone: '',
    room_id: '',
    check_in_date: '',
    check_out_date: '',
  });

  const availableRooms = rooms.filter(
    (room) => room.status === 'not_reserved' && /^([AB]10?|A[1-9]|B[1-9])$/.test(room.room_number)
  );

  const handleSubmit = (e) => {
    e.preventDefault();

    post('/bookings', {
      onSuccess: () => {
        const paymentURL = `https://quickchart.io/qr?text=https://example.com/payment&size=200`;

        Swal.fire({
          title: 'การจองสำเร็จ',
          html: `
            <p>กรุณาชำระเงินจำนวน <strong>4,500 บาท</strong></p>
            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
              <img src="${paymentURL}" alt="QR Code" width="200" height="200" />
            </div>
          `,
          confirmButtonText: 'เสร็จสิ้น',
        });
      },
      onError: (errors) => {
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: errors.room_id || errors.message || 'กรุณาลองใหม่อีกครั้ง',
        });
      },
    });
  };

  return (
    <AuthenticatedLayout>
      <div className="p-8 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg shadow-lg max-w-4xl mx-auto">
        <h1 className="text-3xl font-bold text-center text-black-700 mb-6">Create Booking</h1>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label className="block mb-2 text-lg font-medium text-gray-700">Customer Name</label>
            <input
              type="text"
              value={data.customer_name}
              onChange={(e) => setData('customer_name', e.target.value)}
              className="border p-3 w-full rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            {errors.customer_name && <div className="text-red-500 mt-1">{errors.customer_name}</div>}
          </div>

          <div>
            <label className="block mb-2 text-lg font-medium text-gray-700">Customer Phone</label>
            <input
              type="text"
              value={data.customer_phone}
              onChange={(e) => setData('customer_phone', e.target.value)}
              className="border p-3 w-full rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            {errors.customer_phone && <div className="text-red-500 mt-1">{errors.customer_phone}</div>}
          </div>

          <div>
            <label className="block mb-2 text-lg font-medium text-gray-700">Room Number</label>
            <select
              value={data.room_id || ''}
              onChange={(e) => setData('room_id', e.target.value)}
              className="border p-3 w-full rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
              <option value="">-- เลือกหมายเลขห้อง --</option>
              {availableRooms.map((room) => (
                <option key={room.id} value={room.id}>
                  {room.room_number}
                </option>
              ))}
            </select>
            {errors.room_id && <div className="text-red-500 mt-1">{errors.room_id}</div>}
          </div>

          <div>
            <label className="block mb-2 text-lg font-medium text-gray-700">Check-in Date</label>
            <input
              type="date"
              value={data.check_in_date}
              min={new Date().toISOString().split('T')[0]}
              onChange={(e) => setData('check_in_date', e.target.value)}
              className="border p-3 w-full rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            {errors.check_in_date && <div className="text-red-500 mt-1">{errors.check_in_date}</div>}
          </div>

          <div>
            <label className="block mb-2 text-lg font-medium text-gray-700">Check-out Date</label>
            <input
              type="date"
              value={data.check_out_date}
              min={data.check_in_date || new Date().toISOString().split('T')[0]}
              onChange={(e) => setData('check_out_date', e.target.value)}
              className="border p-3 w-full rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
            {errors.check_out_date && <div className="text-red-500 mt-1">{errors.check_out_date}</div>}
          </div>

          <button
            type="submit"
            className="p-3 w-full rounded-lg shadow-lg text-lg font-semibold transition bg-blue-600 text-white hover:bg-blue-700"
          >
            Submit
          </button>
        </form>
      </div>
    </AuthenticatedLayout>
  );
}
