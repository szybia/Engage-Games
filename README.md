# EngageGames
#### Full Stack Web Development Assignment
#### Game selling web application utilizing Bootstrap, jQuery, AJAX, PHP, SQL and JS  

## Personal Demo
  - https://youtu.be/w-IQOgN1-MI

### Security attack prevention:  
- SQL Injection  
- Cross site scripting (XSS)  
- Cross site request forgery (CSRF) 

### Features:
- Forgot password email authorization  
  - 15 minute link expiration
  - 70 character key along with email for ensured security  
* Remember me cookie with selector and verifier  
  - When user has cookie we search for selector in DB and compare with verifier
- Google reCAPTCHA for register and delete account  
* Advanced search with 2^7 possible searches
- AJAX adding, removing, updating and undoing shopping cart for improved UX
  * JS used for smooth transitions
* Profile picture add, change and delete
  - Security checks ensure no malicious file is uploaded (php, js etc.)
- PHP POST Security
  * All inputs put through size, REGEX and format checks.
* Catalogue with sorting algorithm for newest, low-high, high-low or default random
- Change name, email, password functionality with all required error checking
* Include PHP files check for direct calls and reject them
- 404 page included to ensure user doesn't get derailed from their experience
* Slideshow on home page for highlighting offers and deals
- Static design implemented before development for boosted productivity

### Resources:
- XAMPP
* Google Chrome Developer Tools
- Atom Text Editor
* PHPMyAdmin
- Adobe Photoshop
