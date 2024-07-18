import 'package:flutter/material.dart';

void main() => runApp(const ListaAlumnos());

class ListaAlumnos extends StatelessWidget {
  const ListaAlumnos({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Material App',
      home: Scaffold(
        appBar: AppBar(
          title: const Text('Lista'),
        ),
        body: const Center(
          child: Text('Hello World'),
        ),
      ),
    );
  }
}