import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:smart_flood/models/weather_data.dart';

class WeatherService {
  static const String API_KEY = 'your_weather_api_key';
  static const String BASE_URL = 'https://api.openweathermap.org/data/2.5';

  Future<WeatherData> getWeather(double lat, double lng) async {
    final url =
        '$BASE_URL/weather?lat=$lat&lon=$lng&appid=$API_KEY&units=metric';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      return WeatherData.fromJson(json.decode(response.body));
    } else {
      throw Exception('Failed to load weather data');
    }
  }

  Future<Map<String, dynamic>> getWeatherForecast(
    double lat,
    double lng,
  ) async {
    final url =
        '$BASE_URL/forecast?lat=$lat&lon=$lng&appid=$API_KEY&units=metric';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load weather forecast');
    }
  }
}
