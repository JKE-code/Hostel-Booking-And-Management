# Room Auto-Assignment System

## Overview
The system now automatically assigns specific room numbers to bookings when they are created, ensuring no double-booking and optimal room utilization.

## How It Works

### 1. Auto-Assignment Logic
When a booking is created, the system:
1. Checks which room types were booked (e.g., 2x AC, 1x Suite)
2. For each room type, finds available rooms that are NOT booked during the requested dates
3. Automatically assigns the first available room numbers
4. Records the assignments in the `booking_room_assignments` table

### 2. Availability Check
A room is considered available if:
- It belongs to the requested room type
- It has NO overlapping bookings for the requested dates
- Overlapping means any booking where:
  - Check-in is before or on the requested check-in AND check-out is after the requested check-in
  - OR check-in is before the requested check-out AND check-out is on or after the requested check-out
  - OR check-in is on or after the requested check-in AND check-out is on or before the requested check-out

### 3. Integration Points

#### Online Bookings (user/booking_confirm.php)
- Auto-assignment happens AFTER booking is created
- Runs automatically when payment is confirmed
- If assignment fails, booking still succeeds (can be assigned manually later)

#### Offline Bookings (admin/offline_booking_process.php)
- Auto-assignment happens AFTER booking is created
- Runs automatically when admin completes walk-in booking
- Admin ID is recorded for tracking who processed the booking

### 4. Manual Assignment Tool
If auto-assignment fails or for existing bookings without assignments:
- Go to: `admin/assign_rooms_to_bookings.php`
- Script finds all bookings without room assignments
- Attempts to assign rooms automatically
- Shows success/failure for each booking

## Room Status Display (admin/rooms.php)

### Current Status
- **Green (Available)**: Room has no booking for today's date
- **Red (Occupied)**: Room has a confirmed/checkedin booking for today's date

### Click Any Room
Shows modal with:
1. **Current Status**: Available or Occupied
2. **Current Guest** (if occupied):
   - Guest name, mobile, email
   - Booking reference
   - Check-in and check-out dates
3. **Booking History**:
   - All past, current, and future bookings for that specific room
   - Each booking shows: Guest name, dates, booking ref, status
   - Color-coded by status:
     - Blue: Confirmed
     - Yellow: Checked-in
     - Green: Checked-out

## Database Tables

### booking_room_assignments
Tracks which specific room is assigned to which booking:
- `booking_id`: The booking
- `room_id`: The specific room (e.g., Room 108)
- `assigned_by_admin_id`: Who assigned it (NULL for auto-assignment)
- `assigned_at`: When it was assigned

### booking_rooms
Tracks which room TYPES were booked:
- `booking_id`: The booking
- `room_type_id`: The room type (e.g., AC, Suite)
- `rooms_booked`: How many rooms of this type
- `price_per_night`: Price per night for this room type

## Example Scenario

### Booking Creation:
- Guest books 2x AC rooms from Feb 20-24
- System finds AC rooms: 104, 105, 107, 109, 110, etc.
- Checks which are available Feb 20-24
- Let's say 104 and 107 are occupied, but 105 and 109 are free
- System assigns: Room 105 and Room 109
- Records in `booking_room_assignments` table

### Rooms Dashboard:
- On Feb 20-24: Rooms 105 and 109 show RED (occupied)
- Click Room 105: Shows current guest + all booking history for Room 105
- Click Room 104: Shows different guest (if occupied) or available

### After Checkout:
- On Feb 25: Rooms 105 and 109 show GREEN (available)
- But booking history still shows the past booking

## Benefits

1. **No Double-Booking**: System prevents assigning same room to overlapping dates
2. **Automatic**: No manual work needed for room assignment
3. **Transparent**: Admin can see exactly which room is assigned to which guest
4. **Historical**: Complete booking history per room for analysis
5. **Flexible**: If auto-assignment fails, can be done manually later

## Troubleshooting

### "Not enough available rooms" Error
- Means all rooms of that type are booked for those dates
- Check if dates are correct
- Check if room type has enough total rooms
- May need to manually adjust existing bookings

### Booking Created But No Rooms Assigned
- Check error logs for assignment failure reason
- Run manual assignment script: `admin/assign_rooms_to_bookings.php`
- Verify `booking_room_assignments` table exists

### Room Shows Wrong Status
- Verify booking status is 'confirmed' or 'checkedin'
- Check date range matches today's date
- Clear browser cache and refresh

## Future Enhancements

Possible improvements:
1. Room preferences (guest requests specific room number)
2. Room blocking for maintenance
3. Automatic room upgrade logic
4. Room change functionality (move guest to different room)
5. Housekeeping status integration
