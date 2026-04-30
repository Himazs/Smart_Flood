class User {
  final int id;
  final String name;
  final String email;
  final String userType;
  final String? phone;
  final String? address;
  final String? profileImage;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.userType,
    this.phone,
    this.address,
    this.profileImage,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      userType: json['user_type'],
      phone: json['phone'],
      address: json['address'],
      profileImage: json['profile_image'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'user_type': userType,
      'phone': phone,
      'address': address,
      'profile_image': profileImage,
    };
  }
}
