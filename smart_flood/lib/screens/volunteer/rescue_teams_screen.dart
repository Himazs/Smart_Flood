import 'package:flutter/material.dart';
import 'package:smart_flood/services/api_service.dart';

class RescueTeamsScreen extends StatefulWidget {
  @override
  _RescueTeamsScreenState createState() => _RescueTeamsScreenState();
}

class _RescueTeamsScreenState extends State<RescueTeamsScreen> {
  List<dynamic> _rescueTeams = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadRescueTeams();
  }

  Future<void> _loadRescueTeams() async {
    try {
      final teams = await ApiService().getRescueTeams();
      setState(() {
        _rescueTeams = teams;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to load rescue teams: $e')),
      );
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Rescue Teams')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _rescueTeams.isEmpty
          ? Center(child: Text('No rescue teams available'))
          : ListView.builder(
              padding: EdgeInsets.all(16.0),
              itemCount: _rescueTeams.length,
              itemBuilder: (context, index) {
                return Card(
                  margin: EdgeInsets.only(bottom: 16.0),
                  child: Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          _rescueTeams[index]['name'],
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 8.0),
                        Text('Address: ${_rescueTeams[index]['address']}'),
                        SizedBox(height: 4.0),
                        Text('Contact: ${_rescueTeams[index]['contact']}'),
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }
}
