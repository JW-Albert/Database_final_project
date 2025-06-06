# FCU CSIE Department Website - 0.5.6

This is the official website for the Department of Computer Science and Information Engineering at Feng Chia University.

## Project Structure

```
public_html/
├── js/
│   └── config.js         # Configuration file for BASE_PATH
├── members/
│   ├── chairman/         # Department chair information
│   ├── chair/           # Chair professors
│   ├── distinguished/    # Distinguished professors
│   ├── distinguished_chair/  # Distinguished chair professors
│   ├── faculties/       # Faculty member details
│   ├── full_time/       # Full-time professors
│   ├── honorary/        # Honorary professors
│   ├── part_time/       # Part-time professors
│   ├── retired/         # Retired professors
│   └── staff/           # Administrative staff
├── pics/                # Images and photos
└── index.html          # Main page
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
