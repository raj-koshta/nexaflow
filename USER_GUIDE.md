# NexaFlow - Complete User Guide

Welcome to **NexaFlow**, your next-generation AI-Powered CRM and Business Management platform. This guide explains how to use each module and section of the application effectively.

---

## 1. Dashboard
The Dashboard is your command center. When you log in, you will see a high-level overview of your business metrics.
- **Widgets:** Displays total clients, active projects, new leads, and pending tasks.
- **Activity Feed:** Shows real-time updates of actions taken across the platform by you and your team.
- **Quick Actions:** Use the floating action button (or header shortcuts) to quickly create new Leads, Tasks, or Projects.

---

## 2. CRM (Customer Relationship Management)
The CRM section helps you manage your sales pipeline and customer relationships.

### Leads
- **Purpose:** Track potential clients before they convert.
- **How to Use:** Create a lead with a status (New, Contacted, Qualified). Once a lead is ready to become a customer, click the **Convert to Client** button. This automatically migrates their data into a permanent Client record.
- **AI Integration:** Use AI to draft follow-up emails for leads directly from the lead profile.

### Clients & Companies
- **Purpose:** Manage your official customers.
- **How to Use:** View a client's complete history, associated projects, and open support tickets. You can group multiple clients under a single **Company** if dealing with B2B accounts.

### Contacts
- **Purpose:** Keep track of individual people associated with Clients or Companies.
- **How to Use:** Add phone numbers, emails, and job titles to ensure you always know who to reach out to.

---

## 3. Project & Task Management
Organize your work, assign responsibilities, and track progress.

### Projects
- **Purpose:** High-level containers for ongoing work.
- **How to Use:** Create a project, assign it to a client, and set a budget and deadline.
- **AI Task Generation:** Inside a project, click **AI Generate Tasks**. Describe what you want to achieve, and the AI will automatically break the goal down into smaller, actionable tasks and add them to the project.

### Tasks
- **Purpose:** Individual actionable items.
- **How to Use:** Assign tasks to specific team members, set priorities (Low to Urgent), and update statuses (Todo, In Progress, Review, Done). Drag-and-drop features may be available depending on your view.

### Milestones & Follow-ups
- **Purpose:** Track major project phases (Milestones) and schedule reminders to check in with clients (Follow-ups).

---

## 4. AI Workspace
NexaFlow is heavily integrated with AI to speed up your daily workflows.

- **AI Chat Assistant:** A persistent chat interface where you can ask general questions, brainstorm ideas, or ask for code snippets.
- **Business Insights:** Provide your current company data, and the AI will generate SWOT analyses, market trends, and growth strategies.
- **Email Generator:** Input a brief intent (e.g., "Apologize for the delay and offer a 10% discount"), and the AI will craft a professional, ready-to-send email.
- **Meeting Notes:** Paste your raw, messy meeting transcripts, and the AI will format them into clean summaries with extracted action items.
- **Report Builder:** Generate comprehensive business reports based on minimal input.
- **Prompt Templates:** Save your most frequently used AI prompts (e.g., "Weekly Marketing Update") for 1-click generation in the future.

---

## 5. Support & Tickets
Provide top-tier customer service natively within the platform.
- **How to Use:** Clients (or you on their behalf) can open Support Tickets.
- **AI Reply Generation:** When viewing a ticket, click **AI Draft Reply**, state your intent, and the AI will read the entire ticket thread and draft a professional response.
- **AI Summarize:** For long, complicated ticket threads, use the AI summarize tool to instantly generate a bulleted summary of the issue.

---

## 6. File Manager
- **Purpose:** Centralized document storage.
- **How to Use:** Upload contracts, invoices, and assets. You can attach these files directly to Projects or Clients.

---

## 7. User Management & Security
Control who has access to your system.
- **Users:** Add team members and assign them roles.
- **Roles & Permissions:** Define exact granular permissions (e.g., "Can View Projects", "Cannot Delete Clients"). Assign roles like 'Administrator', 'Manager', or 'Staff' to users to restrict their access.

---

## 8. Settings
- **General Settings:** Update your company name, logo, and timezones.
- **Redis & WebSockets:** Configure your Redis connection (required for real-time notifications and background queues).
- **Dark Mode:** Toggle between light and dark themes using the sun/moon icon in the top header. Your preference is saved automatically.

---

## 9. Real-time Notifications
- **How it Works:** NexaFlow uses WebSockets (via Laravel Reverb). When a task is assigned to you, or a ticket is updated, you will see a live popup notification and the bell icon in the top right will update instantly without you needing to refresh the page!
- **Local Dev Tip:** Ensure you run `php artisan reverb:start` and `php artisan queue:work` locally so notifications fire in real-time.
