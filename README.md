# 📜 Legislative System Platform

**A comprehensive, full-stack legislative management system** designed to streamline governmental operations, bill tracking, ordinance management, and task assignment workflows.

---

## 🎯 Project Overview

The Legislative System Platform is a modern web-based application engineered to handle complex legislative workflows including bill management, ordinance processing, resolution tracking, and inter-departmental task coordination. Built with a focus on scalability, security, and user experience, this system serves as a centralized hub for legislative operations.

**Built by:** Clinton Ikenna N. Ihegoro  
**Repository:** [Cyzo50218/Urdaneta-Legislative-System](https://github.com/Cyzo50218/Urdaneta-Legislative-System)  
**Status:** Active Development

---

## 💼 Key Features

✨ **Comprehensive Bill & Ordinance Management**
- Real-time bill tracking and ordinance processing
- Draft generation with AI assistance
- Attachment and documentation management

🔐 **Robust User & Access Management**
- Multi-role authentication system
- Profile management and account security
- Staff assignment and delegation workflows

📋 **Task Management & Scheduling**
- Automated task assignment and tracking
- Calendar integration for legislative schedules
- Real-time task notifications

📊 **Advanced Dashboard & Analytics**
- Interactive dashboard with real-time metrics
- Activity logging and audit trails
- Data visualization and reporting

🤖 **AI-Powered Features**
- Intelligent draft generation assistance
- Natural language processing for legislative documents

🔔 **Real-Time Notifications**
- Multi-channel notification system
- Status update tracking
- Alert management

---

## 🛠️ Technology Stack

| Layer | Technologies |
|-------|--------------|
| **Backend** | PHP, Laravel Components |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+) |
| **Database** | MySQL/MariaDB |
| **Build Tools** | Composer, NPM |
| **Deployment** | Vercel, Node.js Server |
| **AI Integration** | Custom AI Assistant Module Gemini |

---

## 📁 Project Structure & Architecture

### Root Level Files
```
├── 📄 index.php              # Main application entry point
├── 📄 routes.php             # Route definitions and handlers
├── 📄 server.js              # Node.js server configuration
├── 📄 ai-server.bat          # AI service batch runner
├── 📄 vercel.json            # Vercel deployment configuration
├── 📦 composer.json          # PHP dependency management
├── 📦 package.json           # Node.js dependency management
├── 🔧 encode.php             # Data encoding utilities
├── 🔧 decode.php             # Data decoding utilities
└── 📄 systemMessage.php      # System messaging configuration
```

### 📂 `/api/` Directory - Core Application Logic
**Purpose:** RESTful API endpoints for all application operations

#### Core API Endpoints
```
api/
├── 📋 bills.php                           # Bill management endpoints
├── 📊 fetchDashboardCount.php             # Dashboard metrics retrieval
├── 📋 fetchMyAssignedOrdinancesCount.php  # Ordinance count queries
├── 📋 fetchMyAssignedOrdinancesList.php   # Ordinance listing
├── ✅ fetchMyTasks.php                    # Task retrieval
├── 🔔 notifications.php                   # Notification management
└── 📎 upload_attachment.php               # File upload handling
```

#### 👤 `/account/` Subdirectory - User Management
**Purpose:** User profile, account settings, and personnel management
```
account/
├── 🔐 auth.php                    # Authentication logic
├── 👥 getProfile.php              # User profile retrieval
├── ✏️ updateProfile.php            # Profile update operations
├── 📝 saveLegislationDetails.php   # Store legislative records
├── 📂 showUsersStaff.php           # Staff directory queries
├── 👔 updateAssignment.php         # Assignment management
├── 📑 addDraftToDatabase.php       # Draft storage
├── 🗑️ deleteRecord.php             # Record deletion
├── 🔔 fetchNotificationList.php    # Notification retrieval
├── ✅ markAsRead.php               # Mark notifications as read
└── ✅ markAllAsRead.php            # Bulk notification marking
```

#### 📅 `/activity/` Subdirectory - Activity Tracking
**Purpose:** Audit trails and recent activity logging
```
activity/
├── 📝 addRecentActivity.php        # Log new activities
└── 📊 fetchRecentActivityList.php  # Retrieve activity history
```

#### 🤖 `/assistant/` Subdirectory - AI Services
**Purpose:** AI-driven draft generation and intelligent assistance
```
assistant/
├── 🧠 generateDraftAi.php         # AI draft generation endpoint
└── 🔧 index.js                     # AI service configuration
```

#### 🗄️ `/connections/` Subdirectory - Database Layer
**Purpose:** Database connectivity and configuration
```
connections/
├── ⚙️ config.php                   # Database configuration
└── 🔗 connection.php               # Connection management
```

#### 📜 `/govcodes/` Subdirectory - Government Code Management
**Purpose:** Legislative code sections storage and management
```
govcodes/
├── 💾 saveSection.php              # Save government code sections
├── 🔄 updateSection.php            # Update code sections
├── 🗑️ deleteSection.php            # Remove sections
├── 📖 fetchPageSections.php        # Retrieve sections by page
└── 📦 saveBulkSections.php         # Batch section operations
```

#### 🛠️ `/helpers/` Subdirectory - Utility Functions
**Purpose:** Reusable helper functions and error handling
```
helpers/
├── ⚠️ errorHelper.php              # Error handling utilities
└── 🐛 logConsoleError.php          # Client-side error logging
```

#### 📱 `/js/` Subdirectory - Frontend Scripts
**Purpose:** Client-side JavaScript logic and utilities
```
js/
├── 📊 loadDashboardDataFast.js       # Fast dashboard loading
├── 📋 fetchMyTasks.js                # Task fetching logic
├── 🎯 fetchMyAssignedOrdinanceCount.js  # Ordinance counting
├── 📜 fetchMyAssignedResolutionCount.js # Resolution counting
├── 📅 fetchUpcomingHearings.js       # Schedule retrieval
├── 🎨 turn.min.js                    # UI framework (minified)
└── 📂 tasks/                         # Task-related scripts
```

#### 🔐 `/loginportal/` Subdirectory - Authentication
**Purpose:** User login, authentication, and session management
```
loginportal/
├── 🔐 auth.php                      # Authentication handler
├── 🚪 logout.php                    # Logout operation
└── 💾 saveAccountDetails.php        # Account details storage
```

#### 📋 `/ordinances/` Subdirectory - Ordinance Management
**Purpose:** Ordinance-specific operations and processing
```
ordinances/
└── 📋 fetchMyAssignedOrdinances.php # Retrieve user's ordinances
```

#### ⚖️ `/resolutions/` Subdirectory - Resolution Management
**Purpose:** Resolution tracking and processing
```
resolutions/
└── 📜 fetchMyAssignedResolutions.php # Retrieve user's resolutions
```

#### 📅 `/schedules/` Subdirectory - Schedule Management
**Purpose:** Hearing schedules, calendar management, and timing
```
schedules/
├── 📖 fetchLegislationDetails.php   # Legislation schedule info
└── 📅 fetchSchedulesApi.php         # Schedule API operations
```

#### ✅ `/tasks/` Subdirectory - Task Operations
**Purpose:** Task creation, assignment, and tracking
```
tasks/
└── [Task-related API endpoints]
```

### 🎨 `/assets/` Directory - Static Resources
**Purpose:** Static frontend resources and media
```
assets/
├── 🎨 /css/                        # Stylesheet files
├── 🖼️ /images/                     # Image assets
└── 📜 /js/                         # Frontend JavaScript files
```

### 🔐 `/credentials/` Directory
**Purpose:** Sensitive configuration and credential management

### 📄 `/pages/` Directory - Page Templates
**Purpose:** Server-side rendered page templates
```
pages/
├── 🏠 loginportal.php              # Login page template
├── 📊 benefitPortalSidebar.php     # Portal navigation sidebar
├── 📂 /adminpage/                  # Admin interface pages
└── 📂 /landingpage/                # Landing page templates
```

### 🧩 `/partials/` Directory - Reusable Components
**Purpose:** Modular, reusable UI components and templates
```
partials/
├── 📄 _page_head.php               # Page header component
├── 📄 _page_head_dashboard.php     # Dashboard-specific header
├── 📄 _page_head_loginportal.php   # Login portal header
├── 🎯 navbar.php                   # Navigation bar component
├── 🗂️ dashboard_sidebar.php         # Dashboard sidebar
├── 📋 add-record-modal.php          # Add record modal
├── 🤖 ai-modal.php                  # AI assistant modal
├── 📅 calendar-edit-modal.php       # Calendar editor modal
├── 🗑️ delete-modal.php              # Delete confirmation modal
├── 🔗 hyperlink-modal.php           # Hyperlink editor modal
├── 🔔 notification-modal.php        # Notifications modal
├── 👤 profile-edit-modal.php        # Profile editor modal
├── 👥 reassign-modal.php            # Assignment modal
├── 🎛️ record-options-modal.php      # Record options menu
├── 🔍 search-modal.php              # Search interface modal
├── 📢 toast.php                     # Toast notification component
├── ✅ add-confirmaction-modal.php   # Action confirmation modal
└── 📄 footer.php                    # Footer component
```

### 📑 `/sections/` Directory - Page Sections
**Purpose:** Organized page section templates
```
sections/
├── 📂 /dashboardpage/              # Dashboard page sections
└── 📂 /landingpage/                # Landing page sections
```

### 📦 `/src/` Directory
**Purpose:** Source code and additional application logic

### 📎 `/uploads/` Directory - User-Generated Content
**Purpose:** File storage for user uploads
```
uploads/
└── 📎 /attachments/                # Bill/ordinance attachments
```

### 🔧 `/vendor/` Directory - Dependencies
**Purpose:** Composer-managed PHP dependencies
```
vendor/
├── autoload.php                    # PSR-4 autoloader
├── /composer/                      # Composer metadata
├── /graham-campbell/               # External libraries
├── /phpoption/                     # Optional value handling
├── /symfony/                       # Symfony components
└── /vlucas/                        # Environment configuration
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+ with PDO support
- MySQL 5.7+ or MariaDB
- Node.js 14+ and NPM
- Composer

### Installation

1. **Clone the Repository**
```bash
git clone https://github.com/Cyzo50218/Urdaneta-Legislative-System.git
cd legislative_system
```

2. **Install PHP Dependencies**
```bash
composer install
```

3. **Install Node Dependencies**
```bash
npm install
```

4. **Configure Database**
```bash
cp .env.example .env
# Edit .env with your database credentials
php artisan migrate
```

5. **Start Development Servers**
```bash
# Start PHP development server
php -S localhost:8000

# Start Node.js server (in another terminal)
node server.js

# Start AI server (in another terminal)
ai-server.bat
```

Visit `http://localhost:8000` to access the application.

---

## 📊 Database Architecture

The system utilizes a relational database structure supporting:
- Users and role management
- Bills, ordinances, and resolutions tracking
- Task assignments and workflows
- Activity and audit logs
- Schedule management
- Attachment storage

---

## 🔒 Security Features

✅ Role-based access control (RBAC)  
✅ Secure authentication mechanism  
✅ Input validation and sanitization  
✅ XSS and CSRF protection  
✅ Secure session management  
✅ Audit logging for compliance  

---

## 📈 Performance Considerations

- Optimized database queries for dashboard metrics
- Client-side caching strategies
- Lazy loading for large datasets
- API response compression
- Asset minification (turn.min.js)

---

## 🤝 Contributing

Contributions are welcome! Please follow the existing code structure and maintain consistency with the project's architecture.

---

## 📄 License

This project is licensed under the terms specified in the LICENSE file.

---

## 👨‍💻 Developer

**Original Developer:** Clint  
**Repository:** [Cyzo50218/Urdaneta-Legislative-System](https://github.com/Cyzo50218/Urdaneta-Legislative-System)

---

**Last Updated:** April 2026  
**Version:** 1.0.0
