class AppConstants {
  static const String appName = 'Smart Flood';
  static const String appVersion = '1.0.0';

  // API endpoints
  static const String baseUrl = 'http://your-domain.com/smart-flood-api';

  // Shared Preferences keys
  static const String userKey = 'user';
  static const String tokenKey = 'token';

  // Weather API
  static const String weatherApiKey = 'your_weather_api_key';

  // Map constants
  static const double defaultLatitude = 7.8731; // Sri Lanka coordinates
  static const double defaultLongitude = 80.7718;
  static const double defaultZoom = 7.0;
}

class AppColors {
  static const Color primary = Color(0xFF1976D2);
  static const Color secondary = Color(0xFF42A5F5);
  static const Color accent = Color(0xFFFF4081);
  static const Color danger = Color(0xFFF44336);
  static const Color warning = Color(0xFFFFC107);
  static const Color success = Color(0xFF4CAF50);
  static const Color info = Color(0xFF2196F3);
}

class AppStrings {
  static const String emergencyAlert = 'Emergency Alert';
  static const String floodWarning = 'Flood Warning';
  static const String safetyTips = 'Safety Tips';
  static const String shelters = 'Shelters';
  static const String safeRoutes = 'Safe Routes';
  static const String governmentNews = 'Government News';
}
