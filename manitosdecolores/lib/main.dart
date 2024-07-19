import 'package:flutter/material.dart';
import 'package:manitosdecolores/loginPage.dart';


void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Inicio',
      home: Navigator( // Wrap your MaterialApp with Navigator
        onGenerateRoute: (settings) {
          return MaterialPageRoute(
            builder: (context) => Scaffold(
              body: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20.0),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        Text(
                          'MANITOS DE COLORES',
                          style: TextStyle(
                            color: Color.fromARGB(255, 0, 60, 255),
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Text(
                          'Jardin infantil',
                          style: TextStyle(
                            fontSize: 15,
                            color: Color.fromARGB(255, 0, 204, 255),
                          ),
                        ),
                      ],
                    ),
                    Container(
                      alignment: Alignment.center,
                      child: Image.asset('assets/img/manitos.png', fit: BoxFit.fill),
                    ),
                    Column(
                      children: [
                        ElevatedButton(
                          onPressed: () {
                              Navigator.push(context,  MaterialPageRoute(builder: (context) => const LoginPage()));
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Color.fromARGB(255, 0, 166, 211),
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(horizontal: 100, vertical: 20),
                            minimumSize: const Size(double.infinity, 50),
                          ),
                          child: const Text(
                            'Iniciar sesión',
                            textAlign: TextAlign.justify,
                            ),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        
                      ],
                    )
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}