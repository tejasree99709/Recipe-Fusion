
# 🍽️ Recipe Ingredient Web Application
A comprehensive web application for managing and sharing recipes. Built using PHP, HTML, CSS, JavaScript, Bootstrap, Ajax, and MySQL, this application allows users to register, log in, add, edit, delete, and view recipes, with features such as ingredient lists, reviews, and ratings.

## 🌟 Features
- User registration and login 🔐
- Add new recipes 🆕
- Edit existing recipes ✏️
- Delete recipes ❌
- View recipes with detailed ingredients and instructions 👩‍🍳
- Search and filter recipes by category or name 🔍
- Review and rate recipes ⭐
- Responsive design using Bootstrap 📱💻
- AJAX for dynamic content updates ⚡

## 🛠️ Technologies Used
- **Frontend**: HTML, CSS, Bootstrap, JavaScript, jQuery
- **Backend**: PHP, MySQL
- **AJAX**: For seamless updates

## 📝 Setup Instructions
Follow these steps to set up the project locally:

### 1. Clone the Repository
```bash
git clone https://github.com//RecipeIngredientApp.git
cd RecipeIngredientApp
```

### 2. Set Up the Database
1. Start your local server using XAMPP, WAMP, or MAMP.
2. Open phpMyAdmin and create a new database named `recipe_ingredient`.
3. Import the SQL file to set up the tables:
   ```sql
      CREATE DATABASE recipe_ingredient;
      USE recipe_ingredient;

     CREATE TABLE `users` (
        `id` int(11) NOT NULL,
        `name` varchar(100) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `city` varchar(100) DEFAULT NULL,
        `gender` enum('Male','Female','Other') DEFAULT NULL,
        `bio` text DEFAULT NULL,
        `profile_image` varchar(255) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp()
     );

      CREATE TABLE `recipes` (
        `id` int(11) NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `instructions` text NOT NULL,
        `image` varchar(255) NOT NULL,
        `created_by` int(11) NOT NULL,
        `video_url` varchar(255) DEFAULT NULL,
        `category` varchar(255) NOT NULL
      );
     CREATE TABLE `reviews` (
        `id` int(11) NOT NULL,
        `recipe_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `rating` int(11) NOT NULL,
        `comment` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp()
   );
   ```

### 3. Run the Application
Open a web browser and navigate to `http://localhost/RecipeIngredientApp`.


![Image](https://github.com/user-attachments/assets/aa1825dd-1026-4dbe-ac02-72a776bea0c0)
![Image](https://github.com/user-attachments/assets/50805ece-d3c4-4961-b44b-0bd46d644d63)
![Image](https://github.com/user-attachments/assets/861231b8-12eb-4140-bfeb-971b5bf97776)
![Image](https://github.com/user-attachments/assets/0828d918-72cf-4f49-8b70-d29427290462)
![Image](https://github.com/user-attachments/assets/b2d72478-7514-42f6-8a24-3b556c88daa7)
![Image](https://github.com/user-attachments/assets/c32d3d58-6e24-4f80-a0da-424c2b382344)
![Image](https://github.com/user-attachments/assets/5016face-63ef-4748-ad63-97b354597d7e)
![Image](https://github.com/user-attachments/assets/6296a191-9135-4856-b494-8e4d8815eed1)
![Image](https://github.com/user-attachments/assets/14fd0898-c4db-4cf3-808c-608638981807)

