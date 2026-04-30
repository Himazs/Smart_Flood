import 'package:flutter/material.dart';

class EmergencyButton extends StatelessWidget {
  final VoidCallback onPressed;
  final String text;

  const EmergencyButton({required this.onPressed, this.text = 'EMERGENCY'});

  @override
  Widget build(BuildContext context) {
    return ElevatedButton(
      onPressed: onPressed,
      child: Text(
        text,
        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
      ),
      style: ElevatedButton.styleFrom(
        primary: Colors.red,
        padding: EdgeInsets.symmetric(horizontal: 30, vertical: 20),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
      ),
    );
  }
}
