import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:smart_flood/models/user.dart';
import 'package:smart_flood/models/emergency.dart';
import 'package:smart_flood/models/shelter.dart';
import 'package:smart_flood/models/safe_route.dart';
import 'package:smart_flood/models/flood_report.dart';
import 'package:smart_flood/models/donation.dart';
import 'package:smart_flood/models/government_message.dart';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/smart-flood-api';

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login.php'),
      body: {'email': email, 'password': password},
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['success']) {
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user', json.encode(data['user']));
        await prefs.setString('token', data['token']);
      }
      return data;
    } else {
      throw Exception('Failed to login');
    }
  }

  Future<Map<String, dynamic>> register(Map<String, dynamic> userData) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register.php'),
      body: userData,
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to register');
    }
  }

  Future<Map<String, dynamic>> reportEmergency(
    double lat,
    double lng,
    String name,
  ) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/emergency.php'),
      headers: {'Authorization': 'Bearer $token'},
      body: {
        'latitude': lat.toString(),
        'longitude': lng.toString(),
        'name': name,
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to report emergency');
    }
  }

  Future<List<Shelter>> getShelters() async {
    final response = await http.get(Uri.parse('$baseUrl/shelters.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      List<Shelter> shelters = [];
      for (var item in data['shelters']) {
        shelters.add(Shelter.fromJson(item));
      }
      return shelters;
    } else {
      throw Exception('Failed to load shelters');
    }
  }

  Future<List<SafeRoute>> getSafeRoutes() async {
    final response = await http.get(Uri.parse('$baseUrl/safe_routes.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      List<SafeRoute> routes = [];
      for (var item in data['routes']) {
        routes.add(SafeRoute.fromJson(item));
      }
      return routes;
    } else {
      throw Exception('Failed to load safe routes');
    }
  }

  Future<Map<String, dynamic>> addSafeRoute(
    Map<String, dynamic> routeData,
  ) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/add_safe_route.php'),
      headers: {'Authorization': 'Bearer $token'},
      body: routeData,
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to add safe route');
    }
  }

  Future<Map<String, dynamic>> getWeather(double lat, double lng) async {
    final response = await http.post(
      Uri.parse('$baseUrl/weather.php'),
      body: {'latitude': lat.toString(), 'longitude': lng.toString()},
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load weather data');
    }
  }

  Future<List<GovernmentMessage>> getGovernmentNews() async {
    final response = await http.get(Uri.parse('$baseUrl/government_news.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      List<GovernmentMessage> news = [];
      for (var item in data['news']) {
        news.add(GovernmentMessage.fromJson(item));
      }
      return news;
    } else {
      throw Exception('Failed to load government news');
    }
  }

  Future<List<dynamic>> getSafetyTips(String category) async {
    final response = await http.post(
      Uri.parse('$baseUrl/safety_tips.php'),
      body: {'category': category},
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['tips'];
    } else {
      throw Exception('Failed to load safety tips');
    }
  }

  Future<List<dynamic>> getAppInstructions() async {
    final response = await http.get(Uri.parse('$baseUrl/app_instructions.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['instructions'];
    } else {
      throw Exception('Failed to load app instructions');
    }
  }

  Future<Map<String, dynamic>> submitFloodReport(FloodReport report) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/flood_report.php'),
      headers: {'Authorization': 'Bearer $token'},
      body: report.toJson(),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to submit flood report');
    }
  }

  Future<Map<String, dynamic>> submitDonation(Donation donation) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/donation.php'),
      headers: {'Authorization': 'Bearer $token'},
      body: donation.toJson(),
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to submit donation');
    }
  }

  Future<List<dynamic>> getRescueTeams() async {
    final response = await http.get(Uri.parse('$baseUrl/rescue_teams.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['teams'];
    } else {
      throw Exception('Failed to load rescue teams');
    }
  }

  Future<List<Emergency>> getActiveEmergencies() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/active_emergencies.php'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      List<Emergency> emergencies = [];
      for (var item in data['emergencies']) {
        emergencies.add(Emergency.fromJson(item));
      }
      return emergencies;
    } else {
      throw Exception('Failed to load active emergencies');
    }
  }

  Future<List<GovernmentMessage>> getGovernmentMessages() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/government_messages.php'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      List<GovernmentMessage> messages = [];
      for (var item in data['messages']) {
        messages.add(GovernmentMessage.fromJson(item));
      }
      return messages;
    } else {
      throw Exception('Failed to load government messages');
    }
  }

  Future<Map<String, dynamic>> updateProfile(
    Map<String, dynamic> profileData,
  ) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.post(
      Uri.parse('$baseUrl/update_profile.php'),
      headers: {'Authorization': 'Bearer $token'},
      body: profileData,
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['success']) {
        await prefs.setString('user', json.encode(data['user']));
      }
      return data;
    } else {
      throw Exception('Failed to update profile');
    }
  }

  Future<Map<String, dynamic>> uploadImage(String imagePath) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    var request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/upload_image.php'),
    );

    request.headers['Authorization'] = 'Bearer $token';
    request.files.add(await http.MultipartFile.fromPath('image', imagePath));

    var response = await request.send();
    final responseData = await response.stream.bytesToString();

    if (response.statusCode == 200) {
      return json.decode(responseData);
    } else {
      throw Exception('Failed to upload image');
    }
  }
}
