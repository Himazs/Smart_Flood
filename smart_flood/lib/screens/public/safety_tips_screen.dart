import 'package:flutter/material.dart';
import 'package:smart_flood/services/api_service.dart';

class SafetyTipsScreen extends StatefulWidget {
  @override
  _SafetyTipsScreenState createState() => _SafetyTipsScreenState();
}

class _SafetyTipsScreenState extends State<SafetyTipsScreen> {
  List<dynamic> _floodTips = [];
  List<dynamic> _firstAidTips = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadSafetyTips();
  }

  Future<void> _loadSafetyTips() async {
    try {
      final floodTips = await ApiService().getSafetyTips('flood');
      final firstAidTips = await ApiService().getSafetyTips('first_aid');

      setState(() {
        _floodTips = floodTips;
        _firstAidTips = firstAidTips;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Failed to load safety tips: $e')));
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text('Safety Tips'),
          bottom: TabBar(
            tabs: [
              Tab(text: 'Flood Safety'),
              Tab(text: 'First Aid'),
            ],
          ),
        ),
        body: _isLoading
            ? Center(child: CircularProgressIndicator())
            : TabBarView(
                children: [
                  _buildTipsList(_floodTips),
                  _buildTipsList(_firstAidTips),
                ],
              ),
      ),
    );
  }

  Widget _buildTipsList(List<dynamic> tips) {
    return tips.isEmpty
        ? Center(child: Text('No tips available'))
        : ListView.builder(
            padding: EdgeInsets.all(16.0),
            itemCount: tips.length,
            itemBuilder: (context, index) {
              return Card(
                margin: EdgeInsets.only(bottom: 16.0),
                child: Padding(
                  padding: EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        tips[index]['title'],
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      SizedBox(height: 8.0),
                      Text(tips[index]['description']),
                    ],
                  ),
                ),
              );
            },
          );
  }
}
