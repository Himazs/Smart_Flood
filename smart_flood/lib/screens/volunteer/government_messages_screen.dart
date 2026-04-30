import 'package:flutter/material.dart';
import 'package:smart_flood/models/government_message.dart';
import 'package:smart_flood/services/api_service.dart';
import 'package:smart_flood/widgets/message_card.dart';

class GovernmentMessagesScreen extends StatefulWidget {
  @override
  _GovernmentMessagesScreenState createState() =>
      _GovernmentMessagesScreenState();
}

class _GovernmentMessagesScreenState extends State<GovernmentMessagesScreen> {
  List<GovernmentMessage> _messages = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadGovernmentMessages();
  }

  Future<void> _loadGovernmentMessages() async {
    try {
      final messages = await ApiService().getGovernmentMessages();
      setState(() {
        _messages = messages;
        _isLoading = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to load government messages: $e')),
      );
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Government Messages')),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _messages.isEmpty
          ? Center(child: Text('No government messages available'))
          : RefreshIndicator(
              onRefresh: _loadGovernmentMessages,
              child: ListView.builder(
                padding: EdgeInsets.all(16.0),
                itemCount: _messages.length,
                itemBuilder: (context, index) {
                  return MessageCard(message: _messages[index]);
                },
              ),
            ),
    );
  }
}
