class Donation {
  final int id;
  final int? userId;
  final String name;
  final String contact;
  final String description;
  final String status;
  final DateTime createdAt;

  Donation({
    required this.id,
    this.userId,
    required this.name,
    required this.contact,
    required this.description,
    required this.status,
    required this.createdAt,
  });

  factory Donation.fromJson(Map<String, dynamic> json) {
    return Donation(
      id: json['id'],
      userId: json['user_id'],
      name: json['name'],
      contact: json['contact'],
      description: json['description'],
      status: json['status'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {'name': name, 'contact': contact, 'description': description};
  }
}
