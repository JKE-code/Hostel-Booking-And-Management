import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'core/theme/app_theme.dart';
import 'presentation/main_shell.dart';
import 'state/app_state.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(
    ChangeNotifierProvider(
      create: (_) => AppState(),
      child: const HitamHostelApp(),
    ),
  );
}

class HitamHostelApp extends StatelessWidget {
  const HitamHostelApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'HITAM Hostel Resident Portal',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: const MainShell(),
    );
  }
}
