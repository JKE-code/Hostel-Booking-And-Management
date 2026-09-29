class MealItem {
  final String dayOfWeek;
  final String mealType; // breakfast, lunch, snacks, dinner
  final String title;
  final List<String> items;
  final String? specialNote;

  const MealItem({
    required this.dayOfWeek,
    required this.mealType,
    required this.title,
    required this.items,
    this.specialNote,
  });
}
