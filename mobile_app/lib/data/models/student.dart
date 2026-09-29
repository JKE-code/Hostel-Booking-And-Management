class StudentProfile {
  final int id;
  final String name;
  final String email;
  final String rollNumber;
  final String department;
  final int yearOfStudy;
  final String phone;
  final String parentName;
  final String parentPhone;
  final String emergencyContact;
  final String bloodGroup;
  final String gender;
  final String hostelName;
  final String hostelCode;
  final String blockName;
  final String roomNumber;
  final String bedIdentifier;

  const StudentProfile({
    required this.id,
    required this.name,
    required this.email,
    required this.rollNumber,
    required this.department,
    required this.yearOfStudy,
    required this.phone,
    required this.parentName,
    required this.parentPhone,
    required this.emergencyContact,
    required this.bloodGroup,
    required this.gender,
    required this.hostelName,
    required this.hostelCode,
    required this.blockName,
    required this.roomNumber,
    required this.bedIdentifier,
  });

  factory StudentProfile.fromJson(Map<String, dynamic> json) {
    return StudentProfile(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      rollNumber: json['roll_number'] as String,
      department: json['department'] as String,
      yearOfStudy: json['year_of_study'] as int,
      phone: json['phone'] as String,
      parentName: json['parent_name'] as String,
      parentPhone: json['parent_phone'] as String,
      emergencyContact: json['emergency_contact'] as String? ?? '',
      bloodGroup: json['blood_group'] as String? ?? 'O+',
      gender: json['gender'] as String,
      hostelName: json['hostel_name'] as String,
      hostelCode: json['hostel_code'] as String,
      blockName: json['block_name'] as String,
      roomNumber: json['room_number'] as String,
      bedIdentifier: json['bed_identifier'] as String,
    );
  }
}
