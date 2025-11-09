# System Architecture & Features

## System Overview

The Interactive Giveaway System is a Laravel-based web application that manages theatre/event giveaways through QR code scanning, form submission, and real-time analytics.

## Technology Stack

### Backend
- **Laravel 10.x** - PHP framework
- **Livewire 3.x** - Full-stack framework for dynamic interfaces
- **MySQL/PostgreSQL** - Database
- **Google Sheets API** - External data storage
- **OpenAI API** - AI-powered joke generation

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Chart.js** - Data visualization
- **Responsive Design** - Mobile-first approach

## Core Features

### 1. QR Code Management
- **500 Unique QR Codes**: Pre-generated for seats 001-500
- **Auto-Reset System**: Codes reset after 24 hours
- **Active/Inactive Status**: Prevents duplicate submissions
- **Seat Tracking**: Links submissions to physical seat numbers

### 2. Public Submission Form
- **Mobile-Optimized**: Responsive design for smartphones
- **Real-Time Validation**: Instant field validation
- **Unique Phone Check**: Prevents multiple entries per user
- **Progressive Enhancement**: Works without JavaScript (forms still submit)

**Form Fields:**
- Full Name (required)
- Phone Number (required, unique)
- Email Address (required)
- Date of Birth (required)
- WhatsApp Opt-in (optional)

### 3. AI Joke Generation
- **Personalized Content**: Uses name and age bracket
- **OpenAI Integration**: GPT-3.5-turbo for joke creation
- **Fallback System**: Pre-written jokes if API fails
- **Theatre-Themed**: Family-friendly entertainment content

**Age Brackets:**
- Under 18
- 18-24
- 25-34
- 35-44
- 45-54
- 55-64
- 65+

### 4. Google Sheets Integration
- **Real-Time Sync**: Instant data logging
- **Structured Format**: Consistent data columns
- **Manual Export**: Also available as CSV
- **Error Handling**: Graceful failure with local storage

**Sheet Columns:**
1. Timestamp
2. Seat/QR ID
3. Name
4. Phone
5. Email
6. Date of Birth
7. WhatsApp Opt-in
8. Personalized Joke

### 5. Admin Dashboard

#### Overview Stats
- **Total Submissions**: All-time count
- **Today's Submissions**: Daily activity
- **WhatsApp Opt-ins**: Marketing list size
- **Unique Seats**: Active QR code usage

#### Visualizations
- **Age Distribution Chart**: Doughnut chart showing demographic breakdown
- **Hourly Submissions**: Line chart for last 24 hours activity
- **Real-Time Updates**: Livewire automatic refresh

#### Management Features
- **Export CSV**: Download all submissions
- **Reset QR Codes**: Manually trigger code reset
- **Recent Submissions Table**: Last 10 entries with details
- **Refresh Data**: Manual dashboard update

### 6. Authentication System
- **Secure Admin Access**: Laravel Sanctum
- **Remember Me**: Session persistence
- **Logout Protection**: CSRF tokens
- **Environment-Based Credentials**: Configurable via .env

## Database Schema

### submissions
```sql
id                  BIGINT AUTO_INCREMENT PRIMARY KEY
seat_qr_id          VARCHAR(10) INDEX
name                VARCHAR(255)
phone               VARCHAR(20) UNIQUE INDEX
email               VARCHAR(255)
date_of_birth       DATE
whatsapp_optin      BOOLEAN DEFAULT FALSE
joke                TEXT NULLABLE
submitted_at        TIMESTAMP
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### qr_codes
```sql
id                  BIGINT AUTO_INCREMENT PRIMARY KEY
code                VARCHAR(10) UNIQUE INDEX
seat_number         INT UNIQUE
is_active           BOOLEAN DEFAULT TRUE
last_used_at        TIMESTAMP NULLABLE
reset_at            TIMESTAMP NULLABLE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### users
```sql
id                  BIGINT AUTO_INCREMENT PRIMARY KEY
name                VARCHAR(255)
email               VARCHAR(255) UNIQUE
email_verified_at   TIMESTAMP NULLABLE
password            VARCHAR(255) HASHED
is_admin            BOOLEAN DEFAULT FALSE
remember_token      VARCHAR(100) NULLABLE
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

## Application Flow

### User Journey
```
1. Guest scans QR code on seat
   ↓
2. Redirected to mobile form with seat ID
   ↓
3. Fills in personal information
   ↓
4. Submits form (validation checks)
   ↓
5. Phone number uniqueness verified
   ↓
6. AI generates personalized joke
   ↓
7. Data saved to database
   ↓
8. Data sent to Google Sheets
   ↓
9. QR code marked as used
   ↓
10. Thank you page with joke displayed
```

### Admin Journey
```
1. Admin logs in via /admin/login
   ↓
2. Views dashboard analytics
   ↓
3. Monitors submissions in real-time
   ↓
4. Exports data as CSV
   ↓
5. Resets expired QR codes
   ↓
6. Logs out securely
```

## Security Features

1. **CSRF Protection**: All forms include CSRF tokens
2. **SQL Injection Prevention**: Eloquent ORM parameterized queries
3. **XSS Protection**: Blade template automatic escaping
4. **Password Hashing**: Bcrypt algorithm
5. **Environment Variables**: Sensitive data in .env
6. **HTTPS Ready**: SSL certificate support
7. **Rate Limiting**: Prevents abuse (can be configured)
8. **Input Validation**: Server-side and client-side

## Performance Optimization

1. **Database Indexing**: On phone and seat_qr_id
2. **Query Optimization**: Eager loading relationships
3. **Asset Bundling**: Vite for frontend compilation
4. **Caching**: Laravel config and route caching
5. **CDN Ready**: Static assets can be served via CDN
6. **Lazy Loading**: Images and non-critical assets

## Scalability Considerations

### Horizontal Scaling
- Stateless application design
- Session storage in database/Redis
- Queue workers for background jobs
- Load balancer compatible

### Vertical Scaling
- Optimized database queries
- Connection pooling support
- Memory-efficient Livewire components

## Error Handling

1. **Try-Catch Blocks**: Around external API calls
2. **Fallback Systems**: Local jokes if OpenAI fails
3. **Logging**: Laravel log files for debugging
4. **User-Friendly Messages**: Clear error communication
5. **Graceful Degradation**: Works even if APIs fail

## Monitoring & Maintenance

### Automated Tasks
- **QR Reset**: Daily cron job at 3 AM
- **Log Rotation**: Weekly cleanup
- **Database Backup**: Daily automated backup

### Manual Monitoring
- Check logs: `storage/logs/laravel.log`
- Database queries: Laravel Telescope (optional)
- Server resources: htop, monitoring tools

## API Rate Limits

### Google Sheets API
- 500 requests per 100 seconds per project
- 100 requests per 100 seconds per user

### OpenAI API
- Tier-based (check your account)
- Implement queue for high volume

## Future Enhancements

### Potential Features
1. **Multiple Events**: Support for different shows/events
2. **Winner Selection**: Random draw from submissions
3. **Email Notifications**: Confirmation and winner emails
4. **SMS Integration**: WhatsApp Business API
5. **Multi-Language**: i18n support
6. **Advanced Analytics**: More detailed reporting
7. **Mobile App**: Native iOS/Android apps
8. **Real-Time Dashboard**: WebSocket updates
9. **A/B Testing**: Form variations
10. **Custom Branding**: Per-event theming

### Technical Improvements
1. **Redis Caching**: For session and cache storage
2. **Queue System**: Background job processing
3. **API Rate Limiting**: Laravel Throttle middleware
4. **Testing Suite**: PHPUnit and Dusk tests
5. **CI/CD Pipeline**: Automated deployments
6. **Docker Support**: Containerization
7. **Kubernetes**: Container orchestration
8. **Monitoring Tools**: New Relic, Datadog integration

## System Requirements

### Minimum
- PHP 8.1+
- MySQL 5.7+ or PostgreSQL 10+
- 512 MB RAM
- 1 GB storage
- Apache/Nginx

### Recommended
- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 14+
- 2 GB RAM
- 5 GB storage
- Nginx with PHP-FPM
- Redis for caching
- SSL certificate

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Accessibility

- WCAG 2.1 Level AA compliance
- Keyboard navigation support
- Screen reader friendly
- Color contrast ratios met
- Focus indicators
- Alt text for images

## Support & Documentation

- README.md - Quick start guide
- API_SETUP.md - External API configuration
- DEPLOYMENT.md - Production deployment guide
- CONTRIBUTING.md - Development guidelines
- Inline code comments - Developer documentation

---

**Last Updated**: November 2025
**Version**: 1.0.0
**License**: MIT
