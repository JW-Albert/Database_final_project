# FCU CSIE Department Website - 0.5.6

This is the official website for the Department of Computer Science and Information Engineering at Feng Chia University.

# Contributor
Due to issues with the data structure, the backend initialization version couldn’t be directly merged into the main branch.
The backend initialization version was contributed by Lance.
 - Lance
 - Andy
 - Rei
 - Albert
 - 陳俊宇

## Project Structure

```
.
├── about/
│   ├── api.php
│   ├── index.css
│   ├── index.html
│   └── pics/
│       ├── 1.jpg
│       ├── 2.jpg
│       └── 3.jpg
├── admin/
├── api/
│   └── department.php
├── contact/
│   ├── api.php
│   ├── index.css
│   └── index.html
├── database.md
├── database_tables.md
├── footer.html
├── header-footer.css
├── header.html
├── index.css
├── index.html
├── js/
│   └── config.js
├── members/
│   ├── api.php
│   ├── chair/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── chairman/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── distinguished/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── distinguished_chair/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── faculties/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── full_time/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── honorary/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── index.css
│   ├── index.html
│   ├── part_time/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   ├── pics/
│   │   ├── default.png
│   │   └── P0001.jpg
│   ├── retired/
│   │   ├── api.php
│   │   ├── index.css
│   │   └── index.html
│   └── staff/
│       ├── api.php
│       ├── index.css
│       └── index.html
├── php/
│   └── config.php
├── pics/
│   ├── fcu_logo.svg
│   ├── iecs_logo.png
│   └── posters/
│       ├── 1.jpg
│       ├── 2.jpg
│       └── 3.jpg
├── README.md
├── README-zh.md
└── taskAsssignment.md
```

## Important Configuration

### BASE_PATH Configuration

The website uses a `BASE_PATH` variable defined in `js/config.js` to handle different deployment environments. This is crucial for:

1. Correct URL generation for all internal links
2. Proper loading of static resources (images, CSS, JavaScript)
3. Consistent navigation across different environments

Example configuration in `js/config.js`:
```javascript
const BASE_PATH = '/~D1210799';  // Development environment
// const BASE_PATH = '';         // Production environment
```

### Path Modifications

When deploying to a different environment, you need to:

1. Update `BASE_PATH` in `js/config.js`
2. Modify image paths in HTML files:
   - Profile photos: `pics/professors/`
   - Department logo: `pics/iecs_logo.png`
   - Other static resources

## Features

- Responsive design for all devices
- Dynamic loading of faculty information
- Detailed professor profiles with:
  - Basic information
  - Courses
  - Education
  - Expertise
  - Experience
  - Publications
  - Projects
  - Awards
  - Lectures

## Technical Stack

- HTML5
- CSS3
- JavaScript (ES6+)
- PHP (for API endpoints)
- MySQL (for database)

## Setup Instructions

1. Clone the repository
2. Configure your web server
3. Set up the database
4. Update `js/config.js` with correct `BASE_PATH`
5. Update image paths in HTML files
6. Deploy to your web server

## Contributing

Please read our contributing guidelines before submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
