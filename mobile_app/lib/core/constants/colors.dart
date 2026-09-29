import 'package:flutter/material.dart';

/// HITAM Brand & Portal Design Tokens (Emerald & Obsidian Night Theme)
class AppColors {
  // Primary Forest & Emerald Palette (Matches Website portal.css & layouts)
  static const Color primary = Color(0xFF042E16); // Deepest Forest Night (#042E16)
  static const Color primaryDark = Color(0xFF02180E); // Obsidian Night (#02180E)
  static const Color primaryMedium = Color(0xFF047857); // Vibrant Emerald (#047857)
  static const Color primaryLight = Color(0xFF065F46); // Rich Emerald Shadow (#065F46)
  static const Color primaryAccent = Color(0xFF10B981); // Electric Emerald Accent (#10B981)
  static const Color primaryBgMint = Color(0xFFECFDF5); // Soft Mint Tint (#ECFDF5)
  static const Color primaryBgMintHover = Color(0xFFD1FAE5); // Mint Hover Tint (#D1FAE5)
  
  // Secondary Accent & Warm Gold
  static const Color accent = Color(0xFFD97706); // Warm Amber Gold (#D97706)
  static const Color accentLight = Color(0xFFFBBF24); // Luminous Amber Gold (#FBBF24)
  static const Color accentDark = Color(0xFFB45309); // Dark Amber Gold
  
  // Surface & Backgrounds
  static const Color background = Color(0xFFF8FAFC); // Clean Neutral Slate (#F8FAFC)
  static const Color surface = Colors.white; // Card White
  static const Color surfaceVariant = Color(0xFFF1F5F9); // Slate 100
  static const Color cardBorder = Color(0xFFCBD5E1); // Crisp Slate 200/300 Border
  static const Color cardBorderLight = Color(0xFFE2E8F0);
  
  // Neutral & High Contrast Typography (High-Contrast Slate 900 as on website)
  static const Color textPrimary = Color(0xFF0F172A); // High-Contrast Obsidian/Slate 900
  static const Color textSecondary = Color(0xFF334155); // Slate 700
  static const Color textMuted = Color(0xFF64748B); // Slate 500
  
  // Status Accents
  static const Color success = Color(0xFF047857); // Emerald 700
  static const Color successBg = Color(0xFFECFDF5); // Soft Mint
  static const Color warning = Color(0xFFD97706); // Amber Gold
  static const Color warningBg = Color(0xFFFFFBEB); // Warm Gold Tint
  static const Color error = Color(0xFFDC2626); // Red 600
  static const Color errorBg = Color(0xFFFEF2F2); // Red Tint
  static const Color info = Color(0xFF0284C7); // Sky Blue 600
  static const Color infoBg = Color(0xFFF0F9FF); // Sky Tint
}
