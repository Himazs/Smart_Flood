import 'package:flutter/material.dart';
import 'package:smart_flood/models/government_message.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/widgets/message_card.dart';

class GovernmentNewsScreen extends StatefulWidget {
  @override
  _GovernmentNewsScreenState createState() => _GovernmentNewsScreenState();
}

class _GovernmentNewsScreenState extends State<GovernmentNewsScreen> {
  List<GovernmentMessage> _news = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadGovernmentNews();
  }

  Future<void> _loadGovernmentNews() async {
    try {
      final news = await ApiService().getGovernmentNews();
      setState(() {
        _news = news;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to load government news: $e')),
      );
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Government News')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _news.isEmpty
          ? Center(child: Text('No government news available'))
          : RefreshIndicator(
              onRefresh: _loadGovernmentNews,
              child: ListView.builder(
                padding: EdgeInsets.all(16.0),
                itemCount: _news.length,
                itemBuilder: (context, index) {
                  return MessageCard(message: _news[index]);
                },
              ),
            ),
    );
  }
}
