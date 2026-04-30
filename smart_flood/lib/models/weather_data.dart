class WeatherData {
  final String location;
  final double temperature;
  final String description;
  final double humidity;
  final double windSpeed;
  final String icon;
  final DateTime lastUpdated;

  WeatherData({
    required this.location,
    required this.temperature,
    required this.description,
    required this.humidity,
    required this.windSpeed,
    required this.icon,
    required this.lastUpdated,
  });

  factory WeatherData.fromJson(Map<String, dynamic> json) {
    return WeatherData(
      location: json['name'],
      temperature: json['main']['temp'].toDouble(),
      description: json['weather'][0]['description'],
      humidity: json['main']['humidity'].toDouble(),
      windSpeed: json['wind']['speed'].toDouble(),
      icon: json['weather'][0]['icon'],
      lastUpdated: DateTime.now(),
    );
  }
}
