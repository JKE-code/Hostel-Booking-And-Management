class MaintenanceComplaint {
  final int id;
  final String category; // electrical, plumbing, wifi, cleaning, carpentry, ac_fan, pest_control, other
  final String title;
  final String description;
  final String priority; // low, medium, high, urgent
  final String status; // submitted, in_progress, resolved, closed
  final String? wardenRemarks;
  final DateTime createdAt;
  final DateTime? resolvedAt;
  final int? resolutionRating; // 1 to 5 stars

  const MaintenanceComplaint({
    required this.id,
    required this.category,
    required this.title,
    required this.description,
    required this.priority,
    required this.status,
    this.wardenRemarks,
    required this.createdAt,
    this.resolvedAt,
    this.resolutionRating,
  });

  String get formattedCategory {
    switch (category) {
      case 'electrical':
        return 'Electrical & Power';
      case 'plumbing':
        return 'Plumbing & Water';
      case 'wifi':
        return 'Wi-Fi & Internet';
      case 'cleaning':
        return 'Housekeeping';
      case 'carpentry':
        return 'Carpentry & Furniture';
      case 'ac_fan':
        return 'Fan & AC';
      case 'pest_control':
        return 'Pest Control';
      default:
        return 'General / Other';
    }
  }
}
