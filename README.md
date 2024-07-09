


![App Screenshot](themes/contactoformulario/preview.png?raw=true)


# PROYECTO PLUGIN: Formulario Contacto

Se programó un plugin para WordPress que incluya un formulario de contacto y que los usuarios pueden insertar en cualquier página o entrada utilizando un shortcode. 
Al enviar el formulario, los datos deben enviarse a través de la **API REST** de WordPress y almacenarse en la base de datos. 
Además, se proporcionó una página en el panel de administración para ver las entradas del formulario.

# Funcionalidades del Plugin:

- Configurar la estructura básica del plugin.
- Crear un formulario de contacto que se pueda insertar mediante un shortcode.
- Configurar una ruta personalizada de la API REST para recibir y almacenar los datos del formulario.
- Guardar los datos del formulario en una tabla personalizada.
- Crear una página en el panel de administración para listar y ver las entradas del formulario.

# URL Postman

  ## GET - Registro de datos del Formulario (Autenticado)
      - http://localhost/wordpress/wp-json/myCustomForm/v1/formclientes

  ## GET - Filtro por cliente del registro del Formulario
      - http://localhost/wordpress/wp-json/myCustomForm/v1/formcliente/5

  ## POST - Guarda los datos del cliente a la DB (Autenticado)
      - http://localhost/wordpress/wp-json/myCustomForm/v1/formclientes
      
      # JSON:
        {
            "idDetForm": idDetForm,
            "idForm": idForm,
            "label": "label",
            "datos": "datos",
            "codigo": "codigo"
        }
      

## 🛠 Skills
- HTML5: 
- CSS3:
- JQuery
- Bootstrap
- PHP
- MySQL

# PROYECTO THEME: Tema para el Formulario 

Se creó un tema desde personalizado exclusivamente para poder añadir los formularios mediante un ShortCode.

## 🛠 Skills
- HTML5: 
- CSS3:
- JQuery
- PHP

## Herramientas de uso

- Visual Code
- Github
- Icomoon
- Postman

## 🔗 Demo

http://localhost/[wordpress]/

## Servidor Local

```bash
  http://localhost:5500/
```


## Clonar

Clonar mi proyecto con github

```bash
  $ git clone https://github.com/xynth14/Plugin-y-Tema-para-un-formulario-en-WP.git
```
    
## Autor

- [@xynth14](https://www.github.com/xynth14)

## WebSite

- [Portafolio](https://cynthiaquispemarin.web.app/)


## Soporte

Para soporte, email cyn19870@gmail.com.

