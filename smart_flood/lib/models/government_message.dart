class GovernmentMessage {
  final int id;
  final String title;
  final String message;
  final String targetAudience;
  final DateTime createdAt;

  GovernmentMessage({
    required this.id,
    required this.title,
    required this.message,
    required this.targetAudience,
    required this.createdAt,
  });

  factory GovernmentMessage.fromJson(Map<String, dynamic> json) {
    return GovernmentMessage(
      id: json['id'],
      title: json['title'],
      message: json['message'],
      targetAudience: json['target_audience'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
