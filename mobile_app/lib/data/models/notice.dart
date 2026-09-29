class HostelNotice {
  final int id;
  final String title;
  final String content;
  final String category; // general, fee, maintenance, event, academic, rule, emergency
  final String targetAudience; // all, boys_hostel, girls_hostel
  final bool isPinned;
  final DateTime createdAt;

  const HostelNotice({
    required this.id,
    required this.title,
    required this.content,
    required this.category,
    required this.targetAudience,
    required this.isPinned,
    required this.createdAt,
  });
}
