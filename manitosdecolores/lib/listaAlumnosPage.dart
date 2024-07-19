import 'dart:ui';
import 'package:http/http.dart' as http;
import 'dart:convert';


import 'package:flutter/material.dart';
import 'package:manitosdecolores/loginPage.dart';

void main() => runApp(const ListaAlumnos());

class ListaAlumnos extends StatelessWidget {
  const ListaAlumnos({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Material App',
      home: Scaffold(
        appBar: AppBar(
          title: const Text('Lista'),
        ),
       
        body:  SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text("Alumno 1"),
                ],
              ),
              Column(
                
                children: [
                  ElevatedButton(
                  onPressed: () {
                    Navigator.push(
                      context, 
                      MaterialPageRoute(builder: (context) => const LoginPage())
                    );
                  },
                  style: ButtonStyle(
                    alignment: Alignment.center,
                    backgroundColor: const WidgetStatePropertyAll(Color(0xFF00B8D4)),
                    foregroundColor: WidgetStateProperty.all<Color>(Colors.white),
                    ),
                  child: const Text("Editar")),
                ]
                
              ),
              Column(
                children: [
                ElevatedButton(
                  onPressed: (){
                    Navigator.push(
                      context, 
                      MaterialPageRoute(builder: (context) => const LoginPage())
                    );
                  }, 
                  style: const ButtonStyle(
                    alignment: Alignment.center,
                    backgroundColor: WidgetStatePropertyAll(Colors.red),
                    foregroundColor: WidgetStatePropertyAll(Colors.white)
                  ),
                  child: const Text("Borrar"))]
                
              )
            ],
          ),
      ),
    )
    );
  }
}