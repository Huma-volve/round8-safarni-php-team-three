<?php

namespace App\Http\Controllers;

use App\DTOs\FlightSearchDTO;
use App\Helper\ApiResponse;
use App\Http\Requests\Car\bookingCarRequest;
use App\Http\Requests\searchFlightRequest;
use App\Http\Requests\SeatFlightRequest;
use App\Http\Resources\Bookings\BookingResource;
use App\Repositories\BookingRepositories\BookRepository;
use App\Repositories\BookingRepositories\FlightRepositories;
use App\Http\Resources\Flights\FlightSeatResource;
use App\Services\BookingServices\Cars\CarService;
use App\Services\BookingServices\FlightServices;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Payment;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct(
        protected FlightServices $flightServices,
        protected FlightRepositories $flightRepositories,
        protected CarService $carServices,
        protected BookRepository $bookRepository
        )
    {}

    public function searchFlights(searchFlightRequest $request)
    {
        $searchDTO = new FlightSearchDTO(
            from: $request->from,
            to: $request->to,
            departureDate: new \DateTime($request->departure_date),
            returnDate: $request->has('return_date') ? new \DateTime($request->return_date) : null,
            passengers: $request->passengers,
            type: $request->type
        );

        $result = $this->flightServices->search($searchDTO);

        return $this->flightServices->formatSearchResult($result, $searchDTO->type);
    }

    public function getFlightSeat($id)
    {
        $flight = $this->flightRepositories->findById($id);

        return ApiResponse::success(
            data: [
                'flight_seats' => FlightSeatResource::collection($flight->flightSeats),
            ],
            message: 'تم جلب مقاعد الرحلة بنجاح'
        );
    }

    public function bookFlight($flightId, SeatFlightRequest $request)
    {
        return $this->flightServices->bookingFlight($flightId,$request->validated('seat_ids'));
    }

    public function bookCar(bookingCarRequest $request)
    {
        return $this->carServices->bookCar($request->validated());
    }

    public function searchCar(Request $request)
    {
        $search = $request->input('search');
        return $this->carServices->searchCars($search);
    }

    public function getUserBookings(Request $request)
    {
        $request->validate([
            'type' =>'required|string|in:Car,Flight,Room,Tour'
        ]);

        $bookings = $this->bookRepository->getUserBookings($request->type);
        return ApiResponse::success(
            data: BookingResource::collection($bookings),
            message: 'User Bookings are :'
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'tour_schedule_id' => 'required|exists:tour_schedules,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string', // e.g., credit_card, paypal
        ]);

        $user = Auth::user();
        $tour = Tour::findOrFail($request->tour_id);
        $schedule = TourSchedule::where('id', $request->tour_schedule_id)
            ->where('tour_id', $request->tour_id)
            ->firstOrFail();

        if ($schedule->available_slots < $request->quantity) {
            return response()->json(['message' => 'Not enough available slots'], 400);
        }

        // Calculate price (mock logic as per previous assumption)
        $pricePerPerson = 150 * $tour->duration;
        $totalAmount = $pricePerPerson * $request->quantity;

        DB::beginTransaction();
        try {
            // Create Booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'booking_status' => 'confirmed',
                'total_amount' => $totalAmount,
                'payment_status' => 'paid', // Simulating immediate payment
            ]);

            // Create Booking Detail
            BookingDetail::create([
                'booking_id' => $booking->id,
                'bookable_type' => Tour::class,
                'bookable_id' => $tour->id,
                'quantity' => $request->quantity,
                'price_paid' => $totalAmount,
                'additional_info' => ['schedule_id' => $schedule->id],
            ]);

            // Create Payment
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'status' => 'completed',
                'transaction_id' => Str::uuid(),
                'payment_method' => $request->payment_method,
            ]);

            // Update Schedule
            $schedule->decrement('available_slots', $request->quantity);

            DB::commit();

            return response()->json([
                'message' => 'Booking successful',
                'data' => $booking->load('details', 'user')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking failed', 'error' => $e->getMessage()], 500);
        }
    }
}
