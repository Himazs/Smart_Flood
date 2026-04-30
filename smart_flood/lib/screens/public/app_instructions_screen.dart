import 'package:flutter/material.dart';
import 'package:smart_flood/services/api_service.dart';

class AppInstructionsScreen extends StatefulWidget {
  @override
  _AppInstructionsScreenState createState() => _AppInstructionsScreenState();
}

class _AppInstructionsScreenState extends State<AppInstructionsScreen> {
  List<dynamic> _instructions = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadInstructions();
  }

  Future<void> _loadInstructions() async {
    try {
      final instructions = await ApiService().getAppInstructions();
      setState(() {
        _instructions = instructions;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to load instructions: $e')),
      );
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('How to Use the App')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _instructions.isEmpty
          ? Center(child: Text('No instructions available'))
          : ListView.builder(
              padding: EdgeInsets.all(16.0),
              itemCount: _instructions.length,
              itemBuilder: (context, index) {
                return Card(
                  margin: EdgeInsets.only(bottom: 16.0),
                  child: Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Step ${_instructions[index]['step_number']}: ${_instructions[index]['title']}',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 8.0),
                        Text(_instructions[index]['description']),
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }
}
