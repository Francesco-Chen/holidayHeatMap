# 🗓️ Team Holiday Heatmap

A lightweight, visually appealing web application to manage and visualize your team's holiday schedules. Built with vanilla HTML/CSS/JS and a simple PHP backend.

## 🚀 Features

- **Heatmap Calendar:** At a glance, grasp team holiday dynamics. The color gradient indicates how many employees are off on a given day.
- **Employee Management:** Easily add or remove employees from the roster.
- **Personal Vacations:** Employees can select their names and click on dates to toggle their personal vacation days.
- **Holidays & Weekends:** Automatically blocks out weekends and configured public holidays.
- **CEO Mode:** A dedicated view for managers/CEOs to see a high-level summary of the team's availability.
- **Settings:** Configure public rules (e.g., fixed dates, Easter offsets) and collective vacations.
- **Zero Database Setup:** Uses local JSON files (`data/`) for data storage, making it extremely easy to deploy.

## 📸 Screenshots

*(Placeholder: You can replace this section with actual screenshots of your app!)*

## 🛠️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Francesco-Chen/holidayHeatMap.git
   cd holidayHeatMap
   ```

2. **Server Requirements:**
   - Any web server with PHP support (e.g., Apache, Nginx, or PHP's built-in server).
   - Ensure the `data/` directory is writable by the web server (to save `employees.json`, `vacations.json`, and `holidays.json`).

3. **Running locally (for testing):**
   ```bash
   php -S localhost:8000
   ```
   Then open `http://localhost:8000` in your browser.

## 📂 Project Structure

- `index.php`: The main application page for employees to enter their vacation days.
- `api.php`: The backend API handling JSON reading/writing.
- `ceo.php`: CEO/Manager dashboard view.
- `holidays.php`: Settings page to configure public and collective holidays.
- `data/`: Directory where all the JSON data files are stored.
