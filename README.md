# Portfolio MVC

A personal portfolio website built with PHP using a simple MVC structure.

The public site shows projects, skills, a CV download, and a contact form. An admin area is used to manage this content after login.

## Main ideas

- Feature-based MVC: each part (Home, Projects, Skills, Messages, Auth, CV) has its own controllers, models, and views.
- Routing is automatic from the URL, for example /Projects/index.
- Data is stored in JSON files instead of a database.
- The contact form uses reCAPTCHA and sends email through PHPMailer.
- The public site is in English by default and also supports French and Arabic.

## Requirements

- PHP
- Apache with URL rewriting
- Composer dependencies for PHPMailer

## How to run

Place the project in a local server such as XAMPP and open the Public folder in the browser.

The admin panel is available after login. Keep mail and reCAPTCHA keys in local config files and do not publish them.