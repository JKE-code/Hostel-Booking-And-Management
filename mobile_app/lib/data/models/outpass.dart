class Outpass {
  final int id;
  final String leaveType; // day_pass, weekend, vacation, emergency, medical, academic
  final String destination;
  final String reason;
  final DateTime outDatetime;
  final DateTime inDatetime;
  final DateTime? actualReturnDatetime;
  final String status; // pending, approved, rejected, returned, overdue, cancelled
  final String? approvedByName;
  final DateTime? approvedAt;
  final String? rejectionReason;
  final String? qrVerificationCode;

  const Outpass({
    required this.id,
    required this.leaveType,
    required this.destination,
    required this.reason,
    required this.outDatetime,
    required this.inDatetime,
    this.actualReturnDatetime,
    required this.status,
    this.approvedByName,
    this.approvedAt,
    this.rejectionReason,
    this.qrVerificationCode,
  });

  bool get isApproved => status.toLowerCase() == 'approved';
  bool get isPending => status.toLowerCase() == 'pending';
  bool get isRejected => status.toLowerCase() == 'rejected';
  bool get isReturned => status.toLowerCase() == 'returned';
  bool get isOverdue => status.toLowerCase() == 'overdue';

  String get formattedType {
    switch (leaveType) {
      case 'day_pass':
        return 'Day Pass';
      case 'weekend':
        return 'Weekend Leave';
      case 'vacation':
        return 'Vacation';
      case 'emergency':
        return 'Emergency';
      case 'medical':
        return 'Medical Leave';
      case 'academic':
        return 'Academic / Project';
      default:
        return leaveType;
    }
  }
}
