# NexaFlow - Next-Generation AI-Powered CRM & Business Management

NexaFlow is a modern, full-stack CRM (Customer Relationship Management) and business operations platform. It goes beyond traditional CRMs by deeply integrating AI capabilities (via OpenAI) to automate workflows, generate insights, draft emails, and summarize support tickets.

Built with Laravel, it features a highly dynamic AJAX-first interface, ensuring lightning-fast navigation and seamless user experiences without full page reloads.

## 🌟 Key Features

### 🏢 CRM Core
* **Clients & Companies:** Manage B2B and B2C relationships, tracking all associated projects, tasks, and communications.
* **Leads Pipeline:** Track potential customers from discovery to conversion. Includes one-click conversion from Lead to Client.
* **Contacts:** Manage individual contact persons associated with companies and clients.

### ⚙️ Operations & Project Management
* **Projects & Milestones:** Track project progress, budgets, and milestones.
* **Task Management:** Granular task assignments, statuses (Todo, In Progress, Review, Done), and priority tracking.
* **File Manager:** Securely upload, categorize, and manage documents related to clients and projects.

### 🤖 AI Integration (OpenAI)
* **AI Chat Assistant:** Context-aware chatbot to help navigate the CRM or answer business queries.
* **Smart Support Desk:** AI can automatically summarize long support ticket threads and instantly generate drafted replies.
* **Email Generator:** Generate professional emails for follow-ups, pitches, and updates directly inside the platform.
* **Business Insights & Report Generator:** Analyze platform data to generate actionable insights and formatted reports using AI.

### 🔐 Granular Role-Based Access Control (RBAC)
* Powered by Spatie Permissions.
* **Module-Based Access:** Create roles with hyper-specific permissions (e.g., `view leads`, `create clients`, `delete projects`).
* **Adaptive UI:** The sidebar navigation and action buttons dynamically hide themselves if a user lacks the required granular permissions.

### ⚡ Technology Stack
* **Backend:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL / MariaDB
* **Frontend:** Bootstrap 5, jQuery, AJAX (Single Page Application feel)
* **Real-time:** Reverb / WebSockets (for notifications and AI Chat)
* **Cache & Queues:** Redis
* **AI:** OpenAI API

---

## 🚀 Installation & Setup

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL or MariaDB
* Redis (for caching and queues)
* An OpenAI API Key

### Step-by-Step Guide

**1. Clone the repository**
```bash
git clone https://github.com/yourusername/nexaflow.git
cd nexaflow
```

**2. Install Backend Dependencies**
```bash
composer install
```

**3. Install Frontend Dependencies**
```bash
npm install
```

**4. Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```
Open the `.env` file and configure your database, Redis, and OpenAI credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexaflow
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

OPENAI_API_KEY="your-openai-api-key-here"
```

**5. Database Migration & Seeding**
NexaFlow comes with extensive seeders to bootstrap dummy data, admin accounts, and the granular permission system.
```bash
php artisan migrate --seed
php artisan db:seed --class=ModulePermissionsSeeder
php artisan db:seed --class=DummyDataSeeder
```

**6. Compile Frontend Assets**
```bash
npm run build
# OR for local development:
npm run dev
```

**7. Run the Application**
```bash
php artisan serve
```
Visit `http://localhost:8000` in your browser. 
*(If you ran the DummyDataSeeder, log in with `admin@nexaflow.com` / `password`)*

---

## 📖 Usage Guide

For a detailed breakdown of how to use the specific modules (Leads, AI Dashboard, Reports, Ticket Management), please refer to the included **[USER_GUIDE.md](./USER_GUIDE.md)** file.

---

## 🔒 Security Vulnerabilities
If you discover a security vulnerability within NexaFlow, please send an e-mail to the repository maintainer. All security vulnerabilities will be promptly addressed.

## 📄 License
The NexaFlow CRM is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
