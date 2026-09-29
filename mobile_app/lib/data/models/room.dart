class Roommate {
  final String name;
  final String rollNumber;
  final String department;
  final int yearOfStudy;
  final String bedIdentifier;

  const Roommate({
    required this.name,
    required this.rollNumber,
    required this.department,
    required this.yearOfStudy,
    required this.bedIdentifier,
  });
}

class RoomDetails {
  final String roomNumber;
  final String hostelName;
  final String blockName;
  final int floorNumber;
  final int capacity;
  final int occupied;
  final String roomType; // 'AC' or 'Non-AC'
  final String myBed;
  final List<Roommate> roommates;

  const RoomDetails({
    required this.roomNumber,
    required this.hostelName,
    required this.blockName,
    required this.floorNumber,
    required this.capacity,
    required this.occupied,
    required this.roomType,
    required this.myBed,
    required this.roommates,
  });
}
