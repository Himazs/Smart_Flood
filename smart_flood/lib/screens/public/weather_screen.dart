import 'package:flutter/material.dart';
import 'package:smart_flood/services/weather_service.dart';
import 'package:smart_flood/services/location_service.dart';
import 'package:smart_flood/widgets/weather_card.dart';

class WeatherScreen extends StatefulWidget {
  @override
  _WeatherScreenState createState() => _WeatherScreenState();
}

class _WeatherScreenState extends State<WeatherScreen> {
  Map<String, dynamic>? _weatherData;
  bool _isLoading = true;
  String _error = '';

  @override
  void initState() {
    super.initState();
    _loadWeather();
  }

  Future<void> _loadWeather() async {
    try {
      final location = await LocationService().getCurrentLocation();
      final weather = await WeatherService().getWeather(
        location.latitude,
        location.longitude,
      );

      setState(() {
        _weatherData = {
          'temperature': weather.temperature,
          'description': weather.description,
          'humidity': weather.humidity,
          'windSpeed': weather.windSpeed,
          'icon': weather.icon,
          'location': weather.location,
        };
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = 'Failed to load weather data: $e';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Weather')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _error.isNotEmpty
          ? Center(child: Text(_error))
          : _weatherData != null
          ? WeatherCard(weatherData: _weatherData!)
          : Center(child: Text('No weather data available')),
      floatingActionButton: FloatingActionButton(
        onPressed: _loadWeather,
        child: Icon(Icons.refresh),
      ),
    );
  }
}
